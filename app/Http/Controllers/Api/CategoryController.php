<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParamCollection;
use App\Models\Category;
use App\Models\Param;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function filter(Category $category, Request $request)
    {
        $filter = $request->get('filter');

        $categoryIds = Collection::make($category->id);
        if ($category->childs()->exists()) {
            $categoryIds = $categoryIds->merge(
                $category
                    ->childs()
                    ->select(['id'])
                    ->get()
                    ->pluck('id')
            );
        }

        $params = Param::query()
            ->with('character')
            ->select([
                'params.*',
                DB::raw('count(products.id) as count'),
                DB::raw('group_concat(products.id) as product_ids')
            ])
            ->join('product_param', 'product_param.param_id', '=', 'params.id')
            ->leftJoin('products', function (JoinClause $join) use ($categoryIds) {
                $join->on('products.id', '=', 'product_param.product_id')
                    ->whereIn('products.category_id', $categoryIds->toArray());
            })
            ->groupBy('params.id')->get();

        $params = $params->filter(function($item){
            return $item->count > 0;
        });

        if($filter) {
            $productIdsQuery = Product::query()
                ->select('id')
                ->whereIn('category_id', $categoryIds->toArray());

            foreach($filter as $groupId => $selectedParams){
                $productIdsQuery = $productIdsQuery
                    ->with('params')
                    ->whereHas('params', function (Builder $query) use ($groupId, $selectedParams) {
                        $query->whereIn('param_id', array_values($selectedParams))
                            ->where('characteristic_id', $groupId);
                    });
            }
            $productIds = $productIdsQuery->get()->pluck('id');

            $params->map(function ($item) use ($filter, $productIds) {
                $appendForFilter = Arr::has($filter, $item->characteristic_id);
                if ($appendForFilter and $item->count > 0) {
                    $item->count = '+'.$item->count;
                    return $item;
                }

                $count = 0;
                $ex = explode(',', $item->product_ids);
                foreach ($ex as $exItem) {
                    if (in_array(intval($exItem), $productIds->toArray())) {
                        $count++;
                    }
                }

                $item->count = $count;
                return $item;

            });
        }

        return Collection::make(
            ParamCollection::make($params)->toArray($request)
        )->groupBy('group')->toArray();
    }
}