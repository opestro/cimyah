<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Models\Customer;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Tables\UnverifiedVendorTable;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UnverifiedVendorController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/marketplace::unverified-vendor.name'), route('marketplace.unverified-vendors.index'));
    }

    public function index(UnverifiedVendorTable $table)
    {
        $this->pageTitle(trans('plugins/marketplace::unverified-vendor.name'));

        return $table->renderTable();
    }

    public function view(int|string $id)
    {
        $vendor = Customer::query()
            ->where([
                'id' => $id,
                'is_vendor' => true,
            ])
            ->findOrFail($id);

        if ($vendor->vendor_verified_at) {
            return redirect()->route('customers.edit', $vendor->getKey());
        }

        $this->pageTitle(trans('plugins/marketplace::unverified-vendor.verify', ['name' => $vendor->name]));

        Assets::addScriptsDirectly(['vendor/core/plugins/marketplace/js/marketplace-vendor.js']);

        return view('plugins/marketplace::customers.verify-vendor', compact('vendor'));
    }

    public function approveVendor(int|string $id, Request $request)
    {
        $vendor = Customer::query()
            ->where([
                'id' => $id,
                'is_vendor' => true,
                'vendor_verified_at' => null,
            ])
            ->firstOrFail();

        $vendor->vendor_verified_at = Carbon::now();
        $vendor->save();

        event(new UpdatedContentEvent(CUSTOMER_MODULE_SCREEN_NAME, $request, $vendor));

        if (MarketplaceHelper::getSetting('verify_vendor', 1) && ($vendor->store->email || $vendor->email)) {
            EmailHandler::setModule(MARKETPLACE_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'store_name' => $vendor->store->name,
                ])
                ->sendUsingTemplate('vendor-account-approved', $vendor->store->email ?: $vendor->email);
        }

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('marketplace.unverified-vendors.index'))
            ->withUpdatedSuccessMessage();
    }
}
