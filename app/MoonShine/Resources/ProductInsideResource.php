<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductInside;

use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

class ProductInsideResource extends ModelResource
{
    protected string $model = ProductInside::class;

    protected string $title = 'ProductInsides';

    public function fields(): array
    {
        return [
            Block::make([
                Text::make('Закупка','wholesaleprice'),
                Text::make('Ссылка на продукт поставщика', 'url'),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
