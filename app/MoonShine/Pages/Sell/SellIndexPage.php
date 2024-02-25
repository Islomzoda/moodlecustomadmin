<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Sell;

use App\Models\Payment;
use App\MoonShine\Resources\PaymentResource;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\Badge;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Pages\Crud\IndexPage;

class SellIndexPage extends IndexPage
{
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Имя', 'first_name')->nullable(),
                Text::make('Логин', 'telegram_id')->nullable(),
                Text::make('Фамилия', 'last_name')->nullable(),
                Text::make('Имя Пользователя', 'user_name')->link(fn($sell) => $sell != '/' ? 'https://t.me/'. $sell : '')->nullable(),
                Text::make('Тариф', 'tariff'),
                Select::make('Статус', 'status')->options([
                    'active' => 'активно',
                    'waiting' => 'ожидание',
                    'cancel' => 'отмена',
                    'waiting_cancel' => 'ожидание отмены',
                ]),
                Number::make('Цена', 'price'),
                Number::make('Возврат', 'refund'),
                Number::make('Сумма', 'id', fn($id) => Payment::where('sell_id', $id['id'])->sum('paid')),
                Textarea::make('comment', 'comment')->nullable(),
                HasMany::make('Оплаты', 'payments', resource: new PaymentResource())->onlyLink()
            ]),
        ];
    }


    protected function topLayer(): array
    {
        return [
            ...parent::topLayer(),
             ActionButton::make('Импорт', '/api/importMoodleUsers')->canSee(fn() => auth()->user()->moonshine_user_role_id == 1)->method('importUsers')->customAttributes(['class' => 'mb-4']),
             ActionButton::make('mpstats')->canSee(fn() => auth()->user()->moonshine_user_role_id == 1)->method('mpstats')->customAttributes(['class' => 'mb-4']),
        ];
    }

    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }

    public function indexButtons(): array
    {
        return [
            ActionButton::make('Link', '/endpoint')->bulk(),
        ];
    }
}
