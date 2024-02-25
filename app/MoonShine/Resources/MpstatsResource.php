<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Mpstat;

use MoonShine\Fields\Number;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

class MpstatsResource extends ModelResource
{
    protected string $model = Mpstat::class;

    protected string $title = 'Mpstats';

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Number::make('ID_MP', 'mpstats_id')->useOnImport(),
                Text::make('Логин', 'login')->useOnImport(),
                Text::make('Пароль', 'password')->useOnImport(),
                Text::make('Срок действие до', 'expire_at')->useOnImport(),
                Text::make('API Key', 'api_key')->useOnImport(),
                Text::make('Ссылка для входа', 'app_link')->useOnImport(),
                Text::make('ИД Телеграм', 'telegram_id')->useOnImport(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
