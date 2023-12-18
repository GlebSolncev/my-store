<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:import';

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

        $categoriesData = [];
        foreach ($xml->catalog->category as $category) {
            $slug = Str::slug(strval($category)).'-'.$category['id'];
            $categoriesData[] = [
                'api_id' => intval($category['id']),
                'slug' => $slug,
                'title' => strval($category),
                'is_active' => false,
                'is_category' => true,
            ];
        }

        // Upsert all categories in one go
        Category::upsert($categoriesData, ['api_id'], ['slug', 'title', 'is_active', 'is_category']);

        $categoryApiIds = array_column($categoriesData, 'api_id');
        $allCategories = Category::whereIn('api_id', $categoryApiIds)->get()->keyBy('api_id')->toArray();

        // Prepare parent updates
        $updates = [];
        foreach ($xml->catalog->category as $category) {
            $categoryId = intval($category['id']);
            $parentApiId = $category['parentId'] ? intval($category['parentId']) : null;
            if ($parentApiId && isset($allCategories[$parentApiId])) {
                $parentId = $allCategories[$parentApiId]['id'];
                $updates[] = [
                    'id' => $allCategories[$categoryId]['id'],
                    'parent_id' => $parentId,
                ];
            }
        }
        // Perform batch update for parents
        if (count($updates)) {
            $ids = implode(',', array_column($updates, 'id'));
            $updatesSql = 'CASE `id` ';
            foreach ($updates as $update) {
                $updatesSql .= "WHEN {$update['id']} THEN {$update['parent_id']} ";
            }
            $updatesSql .= 'END';
            DB::statement("UPDATE `categories` SET `parent_id` = $updatesSql WHERE `id` IN ($ids)");
        }

        $this->info('Categories: OK');
    }
}
