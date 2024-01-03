<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Param;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleXMLElement;
use XMLReader;

class ImportProductImages extends Command
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
            /** @var Product $product */
            foreach ($updatedProducts as $product) {
                // Если продукт не был недавно создан, пропустить обновление параметров.
                if (! $product->recently_created) {
                    continue;
                }

                // Находим соответствующие данные продукта
                $productParamsData = $paramsData[$product->api_id] ?? null;
                if ($productParamsData) {
                    // Пересинхронизируем параметры продукта
                    $product->params()->sync($productParamsData);
                }

                $productContentData = $contentsData[$product->api_id] ?? null;
                if ($productContentData) {
                    $productContentData['image'] = $this->saveImageLinkToStorage(
                        $productContentData['image']['url'],
                        $productContentData['image']['product-slug']
                    );
                    $product->content()->create($productContentData);
                }

                $productInsideData = $insidesData[$product->api_id] ?? null;
                if ($productInsideData) {
                    $product->inside()->create($productInsideData);
                }
            }

            DB::commit();
            $this->info('The import was successful!');
        } catch (\Exception $e) {
            DB::rollback();
            $this->error('An error occurred: '.$e->getMessage());
        }

    }

    public function saveImageLinkToStorage(string $link, string $slug): string
    {
        $ext = pathinfo($link, PATHINFO_EXTENSION);
        if (! $ext) {
            return '';
        }

        $filename = sprintf('%s.%s', $slug, $ext);
        Storage::disk('product')
            ->put($filename, file_get_contents($link));


        dd(
            Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix()
//        ->put($filename, file_get_contents($link))
        );

        return $filename;
    }
}
