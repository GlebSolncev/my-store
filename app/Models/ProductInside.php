<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInside extends Model
{
    use HasFactory;

    protected $table = 'product_inside';

    protected $fillable = [
        'wholesaleprice',
        'url',
    ];

    protected $primaryKey = 'product_id';

    public $timestamps = false;

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
