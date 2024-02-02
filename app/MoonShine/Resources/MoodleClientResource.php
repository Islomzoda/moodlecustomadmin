<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\MoodleClient;

use MoonShine\Fields\Number;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

class MoodleClientResource extends ModelResource
{
    protected string $model = MoodleClient::class;

    protected string $title = 'Ученики';

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
                Text::make('E - mail', 'email'),
                Text::make('Имя Пользователя', 'password'),
                Text::make('Тариф', 'tariff'),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
