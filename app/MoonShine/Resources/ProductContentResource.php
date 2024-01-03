<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductContent;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\Image;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;

class ProductContentResource extends ModelResource
{
    protected string $model = ProductContent::class;

    protected string $title = 'ProductContents';

    public function getActiveActions(): array
    {
        return ['create', 'view', 'update', 'delete'];
    }

    public function fields(): array
    {
        return [
            Grid::make([
                Column::make([
                    Block::make('Свойства', [
                        Text::make('Название бренда', 'vendor'),
                        Text::make('Код бренда', 'vendorCode'),
                        Switcher::make('Разрешить покупать данный товар', 'available'),
                    ]),
                ])->columnSpan(8),
                Column::make([
                    Block::make('Медиафайлы', [
                        Image::make('Главное изображение', 'image')
                            ->dir('/') // Директория где будут хранится файлы в storage (по умолчанию /)
                            ->disk('product'),
                    ]),
                ])->columnSpan(4),
                Column::make([
                    TinyMce::make('Description', 'description')
                        ->plugins('anchor')
                        ->locale('ru'),
                ])->columnSpan(12)
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
