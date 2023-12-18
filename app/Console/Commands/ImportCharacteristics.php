<?php

namespace App\Console\Commands;

use App\Models\Characteristic;
use App\Models\Param;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ImportCharacteristics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'characters:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $this->warn($now->format('H:i:s v, u'));
        $path = storage_path('app'.DIRECTORY_SEPARATOR.'shop-price-link.xml');
        $xml = simplexml_load_file($path);

        $params = $filters = [];

        foreach ($xml->items->item as $item) {
            foreach ($item->param as $param) {
                $filter = strval($param['name']);
                $filterSlug = Str::slug($filter);
                $params[$filterSlug][] = strval($param);
                if (! isset($filters[$filterSlug])) {
                    $filters[$filterSlug] = $filter;
                }
            }
        }

        // Save all filters in bulk
        $filtersData = [];
        foreach ($filters as $filterSlug => $filter) {
            $filtersData[] = ['slug' => $filterSlug, 'title' => $filter];
        }
        Characteristic::unsetEventDispatcher();
        Characteristic::upsert($filtersData, 'slug');
        $this->info('Filters: OK');

        // Fetch all characteristics once
        $characteristics = Characteristic::query()->get()->keyBy('slug');

        // Save params in bulk
        foreach ($params as $filterSlug => $paramNames) {
            $paramNames = array_unique($paramNames);
            $paramDataToInsert = [];
            foreach ($paramNames as $pName) {
                $paramDataToInsert[] = [
                    'characteristic_id' => $characteristics[$filterSlug]->id,
                    'slug' => Str::slug($pName),
                    'title' => $pName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Param::query()->upsert($paramDataToInsert, 'title');
        }
        $this->info('Params: OK');
    }
}
