<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Characteristic;
use App\Models\Param;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\StackFields;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;

class ParamPage extends ModelResource
{
    protected string $title = 'Parameters';

    protected string $subtitle = 'Parameters for products';

    public string $model = Param::class;

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
                    Text::make(__('moonshine::ui.resource.role_name'), 'slug')
                        ->required()
                        ->useOnImport()
                        ->showOnExport(),
                ]),

                Switcher::make('Отображение', 'is_active')
                    ->useOnImport()->showOnExport(),

            ]),
        ];
    }

    public function filters(): array
    {
        return [
            BelongsTo::make('Базовая категория', 'parent', resource: new CategoryPage())->searchable(),
        ];
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
