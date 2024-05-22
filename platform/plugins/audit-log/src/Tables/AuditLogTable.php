<?php

namespace Botble\AuditLog\Tables;

use Botble\AuditLog\Models\AuditHistory;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\HeaderActions\HeaderAction;
use Illuminate\Contracts\Database\Eloquent\Builder;

class AuditLogTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(AuditHistory::class)
            ->setView('plugins/audit-log::table')
            ->addColumns([
                IdColumn::make(),
                FormattedColumn::make('action')
                    ->title(trans('plugins/audit-log::history.action'))
                    ->alignStart()
                    ->renderUsing(function (FormattedColumn $column) {
                        return view('plugins/audit-log::activity-line', ['history' => $column->getItem()])->render();
                    }),
            ])
            ->addHeaderActions([
                HeaderAction::make('empty')
                    ->label(trans('plugins/audit-log::history.delete_all'))
                    ->icon('ti ti-trash')
                    ->url('javascript:void(0)')
                    ->attributes(['class' => 'empty-activities-logs-button']),
            ])
            ->addAction(DeleteAction::make()->route('audit-log.destroy'))
            ->addBulkAction(DeleteBulkAction::make()->permission('audit-log.destroy'))
            ->queryUsing(fn (Builder $query) => $query->with('user'));
    }
}
