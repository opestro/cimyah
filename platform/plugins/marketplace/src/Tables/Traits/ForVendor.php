<?php

namespace Botble\Marketplace\Tables\Traits;

use Botble\Marketplace\Facades\MarketplaceHelper;

trait ForVendor
{
    public function booted(): void
    {
        $this
            ->setView(MarketplaceHelper::viewPath('vendor-dashboard.table.base'))
            ->bulkChangeUrl(route('marketplace.vendor.table.bulk-change.save'))
            ->bulkChangeDataUrl(route('marketplace.vendor.table.bulk-change.data'))
            ->bulkActionDispatchUrl(route('marketplace.vendor.table.bulk-action.dispatch'))
            ->filterInputUrl(route('marketplace.vendor.table.filter.input'));
    }
}
