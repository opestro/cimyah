<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Models\Discount;
use Botble\JsValidation\Facades\JsValidator;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Http\Requests\Fronts\DiscountRequest;
use Botble\Marketplace\Tables\DiscountTable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DiscountController extends BaseController
{
    public function index(DiscountTable $table)
    {
        $this->pageTitle(__('Coupons'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(__('Create coupon'));

        Assets::addStylesDirectly(['vendor/core/plugins/ecommerce/css/ecommerce.css'])
            ->addScriptsDirectly([
                'vendor/core/plugins/marketplace/js/discount.js',
            ])
            ->addScripts(['timepicker', 'input-mask'])
            ->addStyles(['timepicker']);

        Assets::usingVueJS();

        Assets::addScripts(['form-validation']);

        $jsValidation = JsValidator::formRequest(DiscountRequest::class, '#marketplace-vendor-discount');

        return MarketplaceHelper::view('vendor-dashboard.discounts.create', compact('jsValidation'));
    }

    protected function getStore()
    {
        return auth('customer')->user()->store;
    }

    public function store(DiscountRequest $request)
    {
        $request->merge([
            'can_use_with_promotion' => 0,
        ]);

        if ($request->input('is_unlimited')) {
            $request->merge(['quantity' => null]);
        }

        $discount = new Discount();

        $discount->fill($request->input());

        $discount->store_id = $this->getStore()->id;
        $discount->save();

        event(new CreatedContentEvent(DISCOUNT_MODULE_SCREEN_NAME, $request, $discount));

        return $this
            ->httpResponse()
            ->setNextUrl(route('marketplace.vendor.discounts.index'))
            ->withCreatedSuccessMessage();
    }

    public function destroy(Discount $discount, Request $request)
    {
        if ($discount->store_id !== $this->getStore()->id) {
            abort(403);
        }

        try {
            $discount->delete();

            event(new DeletedContentEvent(DISCOUNT_MODULE_SCREEN_NAME, $request, $discount));

            return $this
                ->httpResponse()
                ->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function postGenerateCoupon()
    {
        do {
            $code = strtoupper(Str::random(12));
        } while (Discount::query()->where('code', $code)->exists());

        return $this
            ->httpResponse()
            ->setData($code);
    }
}
