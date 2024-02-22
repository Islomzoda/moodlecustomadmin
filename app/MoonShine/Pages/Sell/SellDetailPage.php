<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Sell;

use App\MoonShine\Resources\PaymentResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Pages\Crud\DetailPage;

class SellDetailPage extends DetailPage
{
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Number::make('telegram_id', 'telegram_id'),
                Number::make('moodle_id', 'moodle_id'),
                Text::make('Имя', 'first_name'),
                Text::make('Фамилия', 'last_name'),
                Text::make('Имя Пользователя', 'user_name'),
                Text::make('Тариф', 'tariff'),
                Select::make('Статус', 'status')->options([
                    'active' => 'активно',
                    'waiting' => 'ожидание',
                    'cancel' => 'отмена',
                    'waiting_cancel' => 'ожидание отмены',
                ]),
                Number::make('Цена', 'price'),
                Number::make('Возврат', 'refund'),
                Textarea::make('comment', 'comment'),
                HasMany::make('Оплаты', 'payments', resource: new PaymentResource())
            ]),
        ];
    }

    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
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
}
