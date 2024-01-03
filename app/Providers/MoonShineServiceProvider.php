<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use App\MoonShine\Pages\CategoryPage;
use App\MoonShine\Pages\CharacteristicPage;
use App\MoonShine\Pages\ParamPage;
use App\MoonShine\Pages\ProductPage;
use App\MoonShine\Resources\ProductContentResource;
use App\MoonShine\Resources\ProductInsideResource;
use MoonShine\Menu\MenuDivider;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    protected function resources(): array
    {
        return [];
    }

    protected function pages(): array
    {
        return [];
    }

    protected function menu(): array
    {
        return [
            MenuItem::make(__('moonshine::ui.resource.categories_title'),
                new CategoryPage
            )->badge(fn() => Category::count()),

            MenuGroup::make('Фильтр', [
                MenuItem::make('Группы', new CharacteristicPage()),
                MenuItem::make('Параметры', new ParamPage()),
            ]),

            MenuItem::make(trans('Products'),
                new ProductPage
            )->badge(fn() => Product::count()) ,


            MenuDivider::make('Остальное'),

            MenuGroup::make(__('moonshine::ui.resource.system'), [
                MenuItem::make(__('moonshine::ui.resource.admins_title'),
                    new MoonShineUserResource
                ),
                MenuItem::make(__('moonshine::ui.resource.role_title'),
                    new MoonShineUserRoleResource
                ),
            ])->canSee(fn () => request()->routeIs('moonshine.*')),


            MenuItem::make('Docs', 'https://moonshine-laravel.com/docs'),
            MenuItem::make('', new ProductContentResource())->canSee(fn() => false),
            MenuItem::make('', new ProductInsideResource())->canSee(fn() => false),
        ];
    }

    /**
     * @return array{css: string, colors: array, darkColors: array}
     */
    protected function theme(): array
    {
        return [];
    }
}
