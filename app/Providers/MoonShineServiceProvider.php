<?php

declare(strict_types=1);

namespace App\Providers;

use App\MoonShine\Pages\CategoryPage;
use App\MoonShine\Pages\CharacteristicPage;
use App\MoonShine\Pages\ParamPage;
use App\MoonShine\Pages\ProductPage;
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
            MenuGroup::make(static fn () => __('moonshine::ui.resource.system'), [
                MenuItem::make(
                    static fn () => __('moonshine::ui.resource.admins_title'),
                    new MoonShineUserResource()
                ),
                MenuItem::make(
                    static fn () => __('moonshine::ui.resource.role_title'),
                    new MoonShineUserRoleResource()
                ),
            ])->canSee(fn () => request()->routeIs('moonshine.*')),

            MenuItem::make(
                static fn () => __('moonshine::ui.resource.categories_title'),
                new CategoryPage()
            ),

            MenuItem::make(
                static fn () => __('moonshine::ui.resource.characteristics_title'),
                new CharacteristicPage()
            ),

            MenuItem::make(
                static fn () => __('moonshine::ui.resource.params_title'),
                new ParamPage()
            ),

            MenuItem::make(
                static fn () => __('moonshine::ui.resource.products_title'),
                new ProductPage()
            ),
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
