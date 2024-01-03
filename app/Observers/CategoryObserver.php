<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryObserver
{
    public function creating(Category $category)
    {
        $category->setAttribute(
            'slug',
            Str::slug($category->getAttribute('title'))
        );
    }
}
