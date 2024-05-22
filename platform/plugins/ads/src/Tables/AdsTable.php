<?php

namespace Botble\Ads\Tables;

use Botble\Ads\Models\Ads;
use Botble\Base\Facades\Html;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\DateBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\DateColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;

class AdsTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Ads::class)
            ->addColumns([
                IdColumn::make(),
                ImageColumn::make(),
                NameColumn::make()->route('ads.edit'),
                FormattedColumn::make('key')
                    ->title(trans('plugins/ads::ads.shortcode'))
                    ->alignStart()
                    ->getValueUsing(function (FormattedColumn $column) {
                        $value = $column->getItem()->key;

                        if (! function_exists('shortcode')) {
                            return $value;
                        }

                        return shortcode()->generateShortcode('ads', ['key' => $value]);
                    })
                    ->renderUsing(fn (FormattedColumn $column) => Html::tag('code', $column->getValue()))
                    ->copyable()
                    ->copyableState(fn (FormattedColumn $column) => $column->getValue()),
                Column::make('clicked')
                    ->title(trans('plugins/ads::ads.clicked'))
                    ->alignStart(),
                DateColumn::make('expired_at'),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('ads.create'))
            ->addActions([
                EditAction::make()->route('ads.edit'),
                DeleteAction::make()->route('ads.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('ads.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                DateBulkChange::make()->name('expired_at')->title(trans('plugins/ads::ads.expired_at')),
            ])
            ->queryUsing(function ($query) {
                $query->select([
                    'id',
                    'image',
                    'key',
                    'name',
                    'clicked',
                    'expired_at',
                    'status',
                ]);
            });
    }
}
