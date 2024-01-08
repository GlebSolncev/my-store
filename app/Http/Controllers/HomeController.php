<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController
{

    public function index(Request $request)
    {
        $categories = Category::query()->where([
            ['is_active', '=', true],
        ])->whereNull('parent_id')->get();

        return view('home', [
            'categories' => CategoryCollection::make($categories)->toArray($request)
        ]);
    }
}