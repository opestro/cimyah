<?php

namespace Botble\Ecommerce\Models\QueryBuilders;

use Botble\Base\Models\BaseQueryBuilder;

class StoreQueryBuilder extends BaseQueryBuilder
{
    public function wherePublished($column = 'status'): static
    {
        parent::wherePublished($column);

        $this
            ->whereHas('customer', function ($query) {
                $query->where('is_vendor', true)->whereNotNull('vendor_verified_at');
            });

        return $this;
    }
}
