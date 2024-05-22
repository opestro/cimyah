<?php

namespace Botble\Ecommerce\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Ecommerce\Models\ShippingRule;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\YesNoColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class ShippingRuleItemTable extends TableAbstract
{
    protected array $countries;

    public function setup(): void
    {
        $this
            ->model(ShippingRule::class)
            ->addActions([
                EditAction::make()->route('ecommerce.shipping-rule-items.edit'),
                DeleteAction::make()->route('ecommerce.shipping-rule-items.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('shipping_rule_id', function (ShippingRule $item) {
                return $item->shippingRule->name;
            })
            ->editColumn('country', function (ShippingRule $item) {
                return Arr::get(
                    $this->countries,
                    $item->shippingRule->shipping->country
                ) ?: $item->shippingRule->shipping->country;
            })
            ->editColumn('state', function (ShippingRule $item) {
                return $item->state_name ?: '&mdash;';
            })
            ->editColumn('city', function (ShippingRule $item) {
                return $item->city_name ?: '&mdash;';
            })
            ->editColumn('zip_code', function (ShippingRule $item) {
                return $item->zip_code ?: '&mdash;';
            })
            ->editColumn('adjustment_price', function (ShippingRule $item) {
                return ($item->adjustment_price < 0 ? '-' : '') .
                    format_price($item->adjustment_price) .
                    Html::tag(
                        'small',
                        '(' . format_price(max($item->adjustment_price + $item->shippingRule->price, 0)) . ')',
                        ['class' => 'text-info ms-1']
                    );
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->getModel()
            ->query()
            ->with(['shippingRule', 'shippingRule.shipping'])
            ->select([
                'id',
                'shipping_rule_id',
                'country',
                'state',
                'city',
                'adjustment_price',
                'is_enabled',
                'zip_code',
                'created_at',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('shipping_rule_id')
                ->title(trans('plugins/ecommerce::shipping.rule.item.tables.shipping_rule')),
            Column::make('country')
                ->title(trans('plugins/ecommerce::shipping.rule.item.tables.country')),
            Column::make('state')
                ->title(trans('plugins/ecommerce::shipping.rule.item.tables.state')),
            Column::make('city')
                ->title(trans('plugins/ecommerce::shipping.rule.item.tables.city')),
            Column::make('zip_code')
                ->title(trans('plugins/ecommerce::shipping.rule.item.tables.zip_code')),
            Column::make('adjustment_price')
                ->title(trans('plugins/ecommerce::shipping.rule.item.tables.adjustment_price')),
            YesNoColumn::make('is_enabled')
                ->title(trans('plugins/ecommerce::shipping.rule.item.tables.is_enabled')),
            CreatedAtColumn::make(),
        ];
    }

    public function buttons(): array
    {
        $buttons = $this->addCreateButton(route('ecommerce.shipping-rule-items.create'), 'settings.index.shipping');

        if ($this->hasPermission('ecommerce.shipping-rule-items.bulk-import')) {
            $buttons['import'] = [
                'link' => route('ecommerce.shipping-rule-items.bulk-import.index'),
                'text' =>
                    BaseHelper::renderIcon('ti ti-file-import')
                    . trans('plugins/ecommerce::bulk-import.tables.import'),
            ];
        }

        return $buttons;
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('settings.index.shipping'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'adjustment_price' => [
                'title' => trans('plugins/ecommerce::shipping.rule.item.forms.adjustment_price'),
                'type' => 'number',
                'validate' => 'required|numeric',
            ],
            'is_enabled' => [
                'title' => trans('plugins/ecommerce::shipping.rule.item.forms.is_enabled'),
                'type' => 'select',
                'choices' => [
                    '1' => trans('core/base::base.yes'),
                    '0' => trans('core/base::base.no'),
                ],
                'validate' => 'required|in:0,1',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }

    public function getDefaultButtons(): array
    {
        $buttons = parent::getDefaultButtons();

        return array_merge($buttons, ['export']);
    }
}
