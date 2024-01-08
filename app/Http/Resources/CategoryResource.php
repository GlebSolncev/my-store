<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'is_category' => $this->is_category,
            'child' => CategoryCollection::make($this->childs()->where('is_active', true)->get())->toArray($request),
            'has_products' => $this->products()->exists()
        ];
    }
}
