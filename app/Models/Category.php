<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_id',
        'is_active',
        'is_category',
        'slug',
        'title',
        'parent_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_category' => 'boolean',
    ];

    public function childs()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products(){
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
