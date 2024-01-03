<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Param;
use App\Models\Product;
use App\Models\ProductContent;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $reader = new XMLReader;
        $reader->open(storage_path('app'.DIRECTORY_SEPARATOR.'shop-price-link.xml'));

        while ($reader->read() && $reader->name !== 'item') {

        }

        $productsData = collect();
        $paramsData = [];
        $insidesData = [];
        $contentsData = [];
        while ($reader->name === 'item') {
            $xml = new SimpleXMLElement($reader->readOuterXML());
            $productApiId = strval($xml->Id);
            $productSlug = Str::slug($xml->name);

            $productData = [
                'api_id' => $productApiId,
                'name' => strval($xml->name),
                'slug' => $productSlug,
                'category_id' => $allCategories[strval($xml->categoryId)]->id ?? null,
                'price' => floatval($xml->priceuah),
                'quantity' => intval($xml->quantity),
                'stock' => $xml->stock == 'В наличии' ? true : false,
                'recently_created' => true,
            ];

            // массив для хранения id параметров продукта, полученных из XML
            $paramsData[$productApiId] = collect((array) $xml->param)
                ->except('@attributes')
                ->map(fn ($param) => $allParams[Str::slug(strval($param))]->id ?? null)
                ->filter()
                ->unique()
                ->toArray();

            $insidesData[$productApiId] = [
                'wholesaleprice' => floatval($xml->wholesaleprice),
                'url' => strval($xml->url),
            ];

            $contentsData[$productApiId] = [
                'image' => [
                    'url' => strval($xml->image),
                    'product-slug' => $productSlug,
                ],
                'vendor' => strval($xml->vendor),
                'description' => strval($xml->description),
                'vendorCode' => strval($xml->vendorCode),
                'available' => boolval($xml->available),
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
            $limit = 50;
            /** @var Product $product */
            foreach ($updatedProducts as $product) {
                // Если продукт не был недавно создан, пропустить обновление параметров.
//                if (! $product->recently_created) {
//                    continue;
//                }

                // Находим соответствующие данные продукта
                $productParamsData = $paramsData[$product->api_id] ?? null;
                if ($productParamsData) {
                    // Пересинхронизируем параметры продукта
                    $product->params()->sync($productParamsData);
                }

                $productContentData = $contentsData[$product->api_id] ?? null;
                if ($productContentData) {
                    $productContentData['image'] = $this->saveImageLinkToStorage($productContentData['image']);
                    /** @var ProductContent $content */
                    if($content = $product->content){
                        $content->update($productContentData);
                    }else{
                        $product->content()->create($productContentData);
                    }
                }

                $productInsideData = $insidesData[$product->api_id] ?? null;
                if ($productInsideData) {
                    if($inside = $product->inside){
                        $inside->update($productInsideData);
                    }else{
                        $product->inside()->create($productInsideData);
                    }
                }
                if($limit <= 0){
                    $limit = 50;
                    DB::commit();
                }else{
                    --$limit;
                }
                dump($limit);
            }


            $this->info('The import was successful!');
        } catch (\Exception $e) {
            DB::rollback();
            $this->error('An error occurred: '.$e->getMessage());
        }

    }

    public function saveImageLinkToStorage(array $productData): string
    {
        $ext = pathinfo($productData['url'], PATHINFO_EXTENSION);
        if (! $ext) {
            return '';
        }

        $filename = sprintf('%s.%s', $productData['product-slug'], $ext);
        Storage::disk('product')
            ->put($filename, file_get_contents($productData['url']));


        return $filename;
    }
}
