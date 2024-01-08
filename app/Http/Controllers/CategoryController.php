<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Characteristic;
use App\Models\Param;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CategoryController extends Controller
{
    public function index(string $category, Request $request)
    {
        /** @var Category $category */
        $category = Category::query()->where('slug', $category)->first();
        $categoryIds = $category->childs()->select('id')->get()->pluck('id')->toArray();
        $products = Product::query()
            ->whereIn('category_id', array_merge($categoryIds, [$category->id]))
            ->get();


        $filter = Collection::make(
            (new Api\CategoryController())->filter($category, $request)
        );


        return view('category', [
            'category' => $category,
            'products' => $products,
            'filters'  => $filter,
        ]);
    }


    public function filter(string $category, Request $request)
    {
        $paramSelected = $request->get('filter');
        if(!$paramSelected)
            return $this->index($category, $request);

        $category = Category::query()->where([
            ['slug', '=', $category]
        ])->first();

        $productQuery = Product::with('params')
            ->whereIn('category_id', $category->childs()->select('id')->get()->pluck('id'));

        $productIdsByCategory = $productQuery->get()->pluck('id');

        foreach($paramSelected as $characterId => $paramIds){
            $productQuery->whereHas('params', function($query) use($characterId, $paramIds) {
                $query->where('characteristic_id', $characterId);
                $query->whereIn('id', $paramIds);
            });
        }
        $products = $productQuery->orderByDesc('created_at')->get();
        $params = Param::with(['products'])
            ->whereHas('products', function(Builder $query) use($productIdsByCategory){
                $query->whereIn('product_id', $productIdsByCategory);
            })->get()->unique('id')
            ->map(function($param) use($paramSelected){
                $param->checked = in_array($param->id, $paramSelected[$param->characteristic_id] ?? []);
                return $param;
            });

        $filters = $params->groupBy('characteristic_id');
        $characters = Characteristic::query()->whereIn('id', $filters->keys()->toArray());

        $filter = Collection::make(
            (new Api\CategoryController())->filter($category, $request)
        );


        return view('category', [
            'category' => $category,
            'products' => $products,
            'filters' => $filter,
        ]);
    }
}