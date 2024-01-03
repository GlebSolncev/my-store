<?php

namespace App;

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
}