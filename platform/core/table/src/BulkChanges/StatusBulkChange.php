<?php

namespace Botble\Table\BulkChanges;

use Botble\Base\Enums\BaseStatusEnum;
use Illuminate\Validation\Rule;

class StatusBulkChange extends SelectBulkChange
{
    public static function make(array $data = []): static
    {
        return parent::make()
            ->name('status')
            ->title(trans('core/base::tables.status'))
            ->type('customSelect')
            ->choices(BaseStatusEnum::labels())
            ->validate(['required', Rule::in(BaseStatusEnum::values())]);
    }
}
