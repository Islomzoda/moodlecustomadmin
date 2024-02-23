<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Sell;

use App\MoonShine\Resources\PaymentResource;
use Illuminate\Support\Facades\Log;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Pages\Crud\FormPage;

class SellFormPage extends FormPage
{
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Number::make('telegram_id', 'telegram_id')->required()->disabled(),
                Number::make('moodle_id', 'moodle_id')->canSee(fn() => auth()->user()->moonshine_user_role_id == 1),
                Text::make('Группа', 'chat_id')->copy(),
                Text::make('Имя', 'first_name')->copy()->disabled(),
                Text::make('Фамилия', 'last_name')->copy()->disabled(),
                Text::make('Имя Пользователя', 'user_name')->copy()->disabled(),
                Text::make('Тариф', 'tariff')->required(),
                Select::make('Статус', 'status')->options([
                    'active' => 'активно',
                    'waiting' => 'ожидание',
                    'cancel' => 'отмена',
                    'waiting_cancel' => 'ожидание отмены',
                ]),
                Number::make('Цена', 'price'),
                Number::make('Возврат', 'refund'),
                Textarea::make('comment', 'comment'),
                HasMany::make('Оплаты', 'payments', resource: new PaymentResource())->creatable()
            ]),
        ];
    }

    protected function topLayer(): array
    {
        return [
            ...parent::topLayer(),
            ActionButton::make(
                label: 'Проверить',
                url: '/api/check/' . $this->getResource()->getItemID(),
            )->customAttributes(['class' => 'mb-4']),

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
