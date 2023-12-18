<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Characteristic;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\StackFields;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;

class CharacteristicPage extends ModelResource
{
    protected string $title = 'Characteristics';

    protected string $subtitle = 'Characteristics for products';

    public string $model = Characteristic::class;

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

                StackFields::make('Title')->fields([
                    Text::make(__('moonshine::ui.resource.role_name'), 'title')
                        ->required()
                        ->useOnImport()
                        ->showOnExport(),
                ]),

                Switcher::make('Отображение', 'is_active')
                    ->useOnImport()->showOnExport(),
                Switcher::make('Отображение', 'is_filter')
                    ->useOnImport()->showOnExport(),

                Text::make('Количество параметров', 'params', function($item){
                    return $item->params()->count();
                }),
                HasMany::make('Count params', 'params', resource: new ParamPage())
                    ->fields([
                        StackFields::make('Title')->fields([
                            Text::make(__('moonshine::ui.resource.role_name'), 'title')
                                ->required()
                                ->useOnImport()
                                ->showOnExport(),
                        ])
                    ])->limit(5)
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
