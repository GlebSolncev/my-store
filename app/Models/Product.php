<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_id',
        'name',
        'slug',
        'category_id',
        'price',
        'stock',
        'recently_created',
    ];

    protected $casts = [
        'stock' => 'boolean',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function params()
    {
        return $this->belongsToMany(Param::class, 'product_param');
    }

    public function content()
    {
        return $this->hasOne(ProductContent::class);
    }

    public function inside()
    {
        return $this->hasOne(ProductInside::class);
    }
}
