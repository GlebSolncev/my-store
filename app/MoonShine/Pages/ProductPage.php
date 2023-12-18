<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Category;
use App\Models\Product;
use MoonShine\Attributes\Icon;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Image;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\StackFields;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;

#[Icon('heroicons.outline.list-bullet')]
class ProductPage extends ModelResource
{
    protected string $title = 'Products';

    protected string $subtitle = 'Products list';

    public string $model = Product::class;

    protected bool $createInModal = true;

    protected bool $editInModal = true;

    protected string $column = 'title';

    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title(),
        ];
    }

    public function fields(): array
    {
        return [
            Block::make('', [
                ID::make()->sortable()->showOnExport(),

                Text::make(__('moonshine::ui.resource.role_name'), 'name')
                    ->required()
                    ->useOnImport()
                    ->showOnExport(),

                Text::make('цена', 'price', function($m){
                    return sprintf('%s ₴',
                        number_format($m->price, 2, '.', ',')
                    );
                }),

                Switcher::make('Отображение', 'is_active')
                    ->useOnImport()->showOnExport(),
            ]),
        ];
    }

    public function filters(): array
    {
        return [];
    }

    /**
     * @return array{name: string}
     */
    public function rules($item): array
    {
        return [
            'title' => 'required|min:3',
        ];
    }

    public function search(): array
    {
        return ['id', 'title'];
    }
}
