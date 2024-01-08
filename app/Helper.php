<?php

namespace App;

use App\Http\Resources\CategoryCollection;
use App\Models\Category;

/**
 * @classname Helper
 * @package App
 */
class Helper
{
    /**
     * @param  int|float  $value
     * @param  bool  $withCurrency
     * @return string
     */
    public static function formatPrice(int|float $value, bool $withCurrency = false)
    {
        $price = number_format($value, 2, '.', ',');

        if ($withCurrency) return sprintf('%s грн.', $price);// ₴
        return $price;
    }

    public static function getCategories(){
        $categories = Category::query()->where([
            ['is_active', '=', true],
        ])->whereNull('parent_id')->get();

        return CategoryCollection::make($categories)->toArray(request());
    }
}