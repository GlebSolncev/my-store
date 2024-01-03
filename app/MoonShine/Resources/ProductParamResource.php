<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Param;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;

class ProductParamResource extends ModelResource
{
    protected string $model = Param::class;

    protected string $title = 'title';

    protected string $column = 'title';

    public function search(): array
    {
        return [
            'id', 'title'
        ];
    }

    public function fields(): array
    {
        return [
            Block::make([
                Text::make('Наименование', 'title'),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
