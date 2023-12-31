<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Param extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'filter_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_param');
    }

    public function character()
    {
        return $this->belongsTo(Characteristic::class, 'characteristic_id');
    }

    public function parent()
    {
        return $this->belongsTo(Param::class, 'parent_id');
    }
}
