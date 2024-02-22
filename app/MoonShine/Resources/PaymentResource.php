<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;
use App\MoonShine\Pages\Payment\PaymentIndexPage;
use App\MoonShine\Pages\Payment\PaymentFormPage;
use App\MoonShine\Pages\Payment\PaymentDetailPage;

use MoonShine\Resources\ModelResource;

class PaymentResource extends ModelResource
{
    protected string $model = Payment::class;

    protected string $title = 'Payments';

    public function pages(): array
    {
        return [
            PaymentIndexPage::make($this->title()),
            PaymentFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            PaymentDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
