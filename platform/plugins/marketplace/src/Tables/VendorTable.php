<?php

namespace Botble\Marketplace\Tables;

use Botble\Ecommerce\Tables\CustomerTable;
use Botble\Table\Columns\Column;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;

class VendorTable extends CustomerTable
{
    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->getModel()->query()
            ->select([
                'id',
                'name',
                'email',
                'avatar',
                'created_at',
                'status',
                'confirmed_at',
            ])
            ->where('is_vendor', true)
            ->with(['store']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        $columns = parent::columns();

        $columns[] = Column::make('store_name')
            ->title(trans('plugins/marketplace::marketplace.store_name'))
            ->alignStart()
            ->orderable(false)
            ->searchable(false);

        return $columns;
    }
}
