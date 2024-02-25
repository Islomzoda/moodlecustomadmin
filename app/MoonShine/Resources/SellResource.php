<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\MoodleClient;
use App\Services\Mpstats\Mpstats;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sell;
use App\MoonShine\Pages\Sell\SellIndexPage;
use App\MoonShine\Pages\Sell\SellFormPage;
use App\MoonShine\Pages\Sell\SellDetailPage;

use Illuminate\Support\Facades\Log;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;

class SellResource extends ModelResource
{
    protected string $model = Sell::class;

    protected string $title = 'Продажи';
    protected bool $saveFilterState = true;

    public function pages(): array
    {
        return [
            SellIndexPage::make($this->title()),
            SellFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            SellDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    public function search(): array
    {
        return ['price', 'first_name', 'last_name', 'user_name', 'tariff', 'comment', 'telegram_id'];
    }
    public function filters(): array
    {
        return [
            Text::make('tariff'),
            Select::make('Статус', 'status')->options([
                'active' => 'активно',
                'waiting' => 'ожидание',
                'cancel' => 'отмена',
                'waiting_cancel' => 'ожидание отмены',
            ]),
        ];
    }
    public function rules(Model $item): array
    {
        return [];
    }

    public function mpstats(){
        (new Mpstats())->send();
    }
    public function importUsers(){
        $clients = MoodleClient::all();
        $sells = [];
        foreach ($clients as $client){
            $sells[] = [
                'telegram_id' => $client['telegram_id'],
                'moodle_id' => $client['moodle_id'],
                'first_name' => $client['first_name'],
                'last_name' => $client['last_name'],
                'user_name' => $client['user_name'],
                'tariff' => $client['tariff'],
            ];
        }
        Sell::upsert($sells, ['telegram_id'], [       'telegram_id',
            'moodle_id',
            'first_name',
            'last_name',
            'user_name',
            'tariff',]);
        return;
    }
}
