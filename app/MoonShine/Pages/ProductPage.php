<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Helper;
use App\Models\Characteristic;
use App\Models\Product;
use App\MoonShine\Resources\ProductContentResource;
use App\MoonShine\Resources\ProductInsideResource;
use App\MoonShine\Resources\ProductParamResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\Field;
use MoonShine\Fields\ID;
use MoonShine\Fields\Image;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Relationships\HasOne;
use MoonShine\Fields\Select;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;
use MoonShine\QueryTags\QueryTag;
use MoonShine\Resources\ModelResource;


class ProductPage extends ModelResource
{
    protected string $title = 'Products';

    protected string $subtitle = 'Products list';

    public string $model = Product::class;

    protected bool $createInModal = false;

    protected bool $detailInModal = true;

    protected bool $editInModal = false;

    protected string $column = 'title';


    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title(),
        ];
    }

    public function indexFields(): array
    {
        return [
            ID::make()->sortable()->showOnExport(),
            Image::make('Изображение', 'content.image')->disk('product'),
            Text::make(__('moonshine::ui.resource.role_name'), 'name')
                ->useOnImport()->showOnExport(),
            Text::make('Цена', 'price', function($m){
                return Helper::formatPrice($m->price, true);
            }),

            Switcher::make('Отображение', 'stock')
        ];
    }

    public function detailFields(): array
    {
        return [
            Text::make('Наименование', 'name'),
            Text::make('Цена', 'price', function ($m) {
                return Helper::formatPrice($m->price);
            }),
            Switcher::make('Отображение', 'stock')
                ->useOnImport()->showOnExport(),

            HasOne::make('Контент', 'content', resource: new ProductContentResource()),
            HasOne::make('Внутренняя информация', 'inside', resource: new ProductInsideResource()),

            BelongsToMany::make('Параметры','params', function ($item) {
                return "<b style='color:firebrick'>{$item->character->title}</b>: $item->title";
            }, new ProductParamResource())
        ];
    }

    public function fields(): array
    {
        return [
            Grid::make([
                Column::make([
                    Block::make('Настройки', [
                        ID::make()->sortable()->showOnExport(),

                        Text::make(__('moonshine::ui.resource.role_name'), 'name')
                            ->required()
                            ->useOnImport()
                            ->showOnExport(),
                    ]),

                ])->columnSpan(6),
                Column::make([
                    Block::make('Свойства', [
                        Text::make('цена', 'price', function ($m) {
                            return sprintf(
                                '%s ₴',
                                number_format($m->price, 2, '.', ',')
                            );
                        }),

                        Switcher::make('Отображение', 'stock')
                            ->useOnImport()->showOnExport(),
                    ]),
                ])->columnSpan(6),
            ]),

            HasOne::make('Контент', 'content', resource: new ProductContentResource()),
            HasOne::make('Внутренняя информация', 'inside', resource: new ProductInsideResource()),

            BelongsToMany::make('Параметры','params', resource: new ProductParamResource())
                ->valuesQuery(fn(Builder $query, Field $field) => $query->where('is_active', true))
                ->asyncSearch()->columnLabel('title')->selectMode()
        ];
    }

    public function query(): \Illuminate\Contracts\Database\Eloquent\Builder
    {
        if(!request()->get('filters'))
            return parent::query();


        # filter
        foreach(request('filters') as $key => $filter){
            if(strpos($key, 'param') !== false){
                $ex = explode('|', $key);
                $character[end($ex)] = intval($filter);
            }
        }

        return Product::query()
            ->join('product_param', function(JoinClause $j) use($character) {
                $j->on('product_id', '=', 'id')
                    ->whereIn('param_id', $character);
            });
    }

    public function filters(): array
    {
        $filter[] =  Text::make('Наименование','title');

        foreach(Characteristic::query()->where([
            ['is_filter', '=', true],
            ['is_active', '=', true]
        ])->get() as $character){
            $filter[] =  Select::make($character->title, 'param|'.$character->slug)
                ->options($character->params->pluck('title', 'id')->toArray());

        }

        return $filter;
    }

    /**
     * @return array{name: string}
     */
    public function rules($item): array
    {
        return [
            'name' => 'required|min:3',
        ];
    }

    public function search(): array
    {
        return ['id', 'title'];
    }
}
