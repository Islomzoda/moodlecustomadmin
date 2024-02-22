<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Payment;

use MoonShine\Decorations\Block;
use MoonShine\Fields\Date;
use MoonShine\Fields\File;
use MoonShine\Fields\ID;
use MoonShine\Fields\Number;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\DetailPage;

class PaymentDetailPage extends DetailPage
{
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Number::make('Оплачено (Рубль)', 'paid'),
                Select::make('Название банка', 'bank_name')->options([
                    'tinkoff' => 'Тинькофф',
                    'sberbank' => 'Сбербанк',
                ]),
                Select::make('Название банка', 'bank_name')->options([
                    'Shodon' => 'Шодон',
                    'MuhammadT' => 'Мухаммади Т',
                    'MuhammadK' => 'Кузибаев М',
                    'SadridinSh' => 'Садридин Ш',
                    'Khiradmand' => 'Хирадманд',
                ]),
                Text::make('Имя Отправителя', 'transfer_name'),
                Text::make('Переведен с банка', 'transfer_bank'),
                Date::make('Дата и время оплаты (чек)', 'paid_at')->withTime(),
                File::make('Чеки', 'files')
                    ->multiple()
            ])
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
