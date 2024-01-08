<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $character = $this->character;

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'count' => $this->count,
            'group_id' => $character->id,
            'group' => $character->title,
            'checked' => in_array($this->id, $request->get('filter')[$character->id] ?? [])
        ];
    }
}
