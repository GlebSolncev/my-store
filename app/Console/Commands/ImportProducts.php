<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Param;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleXMLElement;
use XMLReader;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    public function handle()
    {
        $allParams = Param::all()->keyBy('slug');
        $allCategories = Category::all()->keyBy('api_id');
        $reader = new XMLReader();
        $reader->open(storage_path('app'.DIRECTORY_SEPARATOR.'shop-price-link.xml'));

        while ($reader->read() && $reader->name !== 'item');

        $productsData = collect();
        $paramsData = [];
        $insidesData = [];
        $contentsData = [];
        while ($reader->name === 'item') {
            $xml = new SimpleXMLElement($reader->readOuterXML());
            $productApiId = strval($xml->Id);

            $productData = [
                'api_id'           => $productApiId,
                'name'             => strval($xml->name),
                'slug'             => Str::slug($xml->name),
                'category_id'      => $allCategories[strval($xml->categoryId)]->id ?? null,
                'price'            => floatval($xml->priceuah),
                'quantity'         => intval($xml->quantity),
                'stock'            => $xml->stock == 'В наличии' ? true : false,
                'recently_created' => true,
            ];

            // массив для хранения id параметров продукта, полученных из XML
            $paramsData[$productApiId] = collect((array) $xml->param)
                ->except('@attributes')
                ->map(fn($param) => $allParams[Str::slug(strval($param))]->id ?? null)
                ->filter()
                ->unique()
                ->toArray();

            $insidesData[$productApiId] = [
                'wholesaleprice'   => floatval($xml->wholesaleprice),
                'url'              => strval($xml->url),
            ];

            $contentsData[$productApiId] = [
                'image'            => strval($xml->image),
                'vendor'           => strval($xml->vendor),
                'description'      => strval($xml->description),
                'vendorCode'       => strval($xml->vendorCode),
                'available'        => boolval($xml->available),
            ];

            $productsData->push($productData);
            $reader->next('item');
        }
        $reader->close();

        DB::beginTransaction();

        try {
            Product::upsert(
                $productsData->toArray(),
                ['api_id'],
                ['name', 'slug', 'category_id', 'price', 'recently_created']
            );

            $productApiIds = $productsData->pluck('api_id');
            $updatedProducts = Product::whereIn('api_id', $productApiIds)->get();
            /** @var Product $product */
            foreach ($updatedProducts as $product) {
                // Если продукт не был недавно создан, пропустить обновление параметров.
                if (!$product->recently_created) {
                    continue;
                }

                // Находим соответствующие данные продукта
                $productParamsData = $paramsData[$product->api_id] ?? null;
                if ($productParamsData) {
                    // Пересинхронизируем параметры продукта
                    $product->params()->sync($productParamsData);
                }

                $productContentData = $contentsData[$product->api_id] ?? null;
                if($productContentData)
                    $product->content()->create($productContentData);

                $productInsideData = $insidesData[$product->api_id] ?? null;
                if($productInsideData)
                    $product->inside()->create($productInsideData);
            }

            DB::commit();
            $this->info('The import was successful!');
        } catch (\Exception $e) {
            DB::rollback();
            $this->error('An error occurred: '.$e->getMessage());
        }

    }

    /**
     * Execute the console command.
     */
    public
    function handleOld()
    {
        $now = Carbon::now();
        $this->warn($now->format('H:i:s v, u'));
        $path = storage_path('app'.DIRECTORY_SEPARATOR.'shop-price-link.xml');
        $xml = simplexml_load_file($path);

        // Fetch all required data in one go
        $categoryIds = $xml->xpath('//categoryId');
        $categories = Category::whereIn('api_id', $categoryIds)->get()->keyBy('api_id');

        $paramNames = $xml->xpath('//param');
        $params = Param::whereIn('slug',
            collect($paramNames)->map(fn($item) => Str::slug($item)))->get()->keyBy('slug');

        $products = [];
        $productParams = [];
        foreach ($xml->items->item as $item) {
            $singleProduct = [
                'api_id'         => strval($item['Id']),
                'name'           => strval($item->name),
                'slug'           => Str::slug($item->name),
                'category_id'    => $category->id ?? null,
                'price'          => floatval($item->priceuah),
                'wholesaleprice' => floatval($item->wholesaleprice),
                'url'            => strval($item->url),
                'image'          => strval($item->image),
                'vendor'         => strval($item->vendor),
                'description'    => strval($item->description),
                'vendorCode'     => strval($item->vendorCode),
                'quantity'       => intval($item->quantity),
                'stock'          => $item->stock == 'В наличии' ? true : false,
                'available'      => boolval($item->available),
            ];

            $singleProduct['params'] = collect((array) $item->param)
                ->except('@attributes')
                ->map(function ($param) use ($params) {
                    return $params[Str::slug($param)]->id ?? null;
                })->toArray();

            $products[] = $singleProduct;
        }

        $this->import($products);

        $this->info('Products: OK');
    }

    protected
    function import(
        array $productsData
    ) {
        DB::beginTransaction();

        try {
            foreach (array_chunk($productsData, 500) as $productsChunk) {
                $productsDataWithoutParams = [];
                $syncData = [];

                foreach ($productsChunk as $productData) {
                    $productParams = $productData['params'];
                    $productsDataWithoutParams[] = Arr::except($productData, ['params']);
                    $syncData[$productData['api_id']] = $productParams;
                }

                Product::upsert($productsDataWithoutParams, ['api_id'], [
                    'name', 'slug', 'category_id', 'price', 'wholesaleprice', 'url', 'image', 'vendor', 'description',
                    'vendorCode', 'quantity', 'stock', 'available'
                ]);

                $products = Product::whereIn('api_id', array_column($productsDataWithoutParams, 'api_id'))->get();

                foreach ($products as $product) {
                    $product->params()->sync($syncData[$product->api_id]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
