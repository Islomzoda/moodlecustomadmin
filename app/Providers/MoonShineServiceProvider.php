<?php

declare(strict_types=1);

namespace App\Providers;

use App\MoonShine\Resources\MoodleClientResource;
use App\MoonShine\Resources\MpstatsResource;
use App\MoonShine\Resources\PaymentResource;
use App\MoonShine\Resources\SellResource;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
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
            MenuGroup::make(static fn() => __('moonshine::ui.resource.system'), [
               MenuItem::make(
                   static fn() => __('moonshine::ui.resource.admins_title'),
                   new MoonShineUserResource()
               ),
               MenuItem::make(
                   static fn() => __('moonshine::ui.resource.role_title'),
                   new MoonShineUserRoleResource()
               ),

            ])->canSee(fn() => auth()->user()->moonshine_user_role_id == 1),
            MenuItem::make(
                'Ученики',
                new MoodleClientResource()
            ),
            MenuItem::make(
                'Продажи',
                new SellResource()
            ),
            MenuItem::make(
                'Оплаты',
                new PaymentResource()
            ),
            MenuItem::make(
                'MPSTATS',
                new MpstatsResource()
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
