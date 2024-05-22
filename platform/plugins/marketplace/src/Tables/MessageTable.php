<?php

namespace Botble\Marketplace\Tables;

use Botble\Marketplace\Models\Message;
use Botble\Marketplace\Tables\Traits\ForVendor;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\Action;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\DateTimeColumn;
use Botble\Table\Columns\EmailColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Illuminate\Http\JsonResponse;

class MessageTable extends TableAbstract
{
    use ForVendor;

    public function setup(): void
    {
        $this
            ->model(Message::class)
            ->addColumns([
                IdColumn::make(),
                Column::make('name'),
                EmailColumn::make(),
                FormattedColumn::make('content')->limit(50),
                DateTimeColumn::make('created_at'),
            ])
            ->addActions([
                Action::make('view')
                    ->label(__('View'))
                    ->icon('ti ti-eye')
                    ->url(fn (Action $action) => route('marketplace.vendor.messages.show', $action->getItem()))
                    ->color('info'),
                DeleteAction::make()->url(fn (Action $action) => route('marketplace.vendor.messages.destroy', $action->getItem())),
            ])
            ->addBulkAction(DeleteBulkAction::make())
            ->onAjax(function (): JsonResponse {
                return $this->toJson(
                    $this
                        ->table
                        ->eloquent($this->query()->where('store_id', auth('customer')->user()->store->id))
                );
            });
    }
}
