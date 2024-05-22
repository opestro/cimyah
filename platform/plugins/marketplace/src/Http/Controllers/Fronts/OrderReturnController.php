<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Enums\OrderReturnStatusEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\OrderReturnHelper;
use Botble\Ecommerce\Http\Requests\UpdateOrderReturnRequest;
use Botble\Ecommerce\Models\OrderReturn;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Tables\OrderReturnTable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class OrderReturnController extends BaseController
{
    public function __construct()
    {
        if (! EcommerceHelper::isOrderReturnEnabled()) {
            abort(404);
        }
    }

    public function index(OrderReturnTable $orderReturnTable)
    {
        $this->pageTitle(trans('plugins/ecommerce::order.order_return'));

        return $orderReturnTable->renderTable();
    }

    protected function getStore()
    {
        return auth('customer')->user()->store;
    }

    public function edit(int|string $id)
    {
        $returnRequest = $this->findOrFail($id);

        $returnRequest->load(['items', 'customer', 'order']);

        Assets::addStylesDirectly(['vendor/core/plugins/ecommerce/css/ecommerce.css'])
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/libraries/jquery.textarea_autosize.js',
                'vendor/core/plugins/ecommerce/js/order.js',
            ])
            ->addScripts(['input-mask']);

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');
        }

        $this->pageTitle(trans('plugins/ecommerce::order.edit_order_return', ['code' => get_order_code($id)]));

        $defaultStore = get_primary_store_locator();

        $returnRequest->loadMissing(['histories' => fn (HasMany $query) => $query->latest()]);

        return MarketplaceHelper::view(
            'vendor-dashboard.order-returns.edit',
            compact('returnRequest', 'defaultStore')
        );
    }

    public function update(int|string $id, UpdateOrderReturnRequest $request)
    {
        $returnRequest = $this->findOrFail($id);

        $returnRequest->load(['items', 'customer', 'order']);

        $data['return_status'] = $request->input('return_status');

        if ($returnRequest->return_status == $data['return_status'] ||
            $returnRequest->return_status == OrderReturnStatusEnum::CANCELED ||
            $returnRequest->return_status == OrderReturnStatusEnum::COMPLETED) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/ecommerce::order.notices.update_return_order_status_error'));
        }

        [$status, $returnRequest] = OrderReturnHelper::updateReturnOrder($returnRequest, $data);

        if (! $status) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/ecommerce::order.notices.update_return_order_status_error'));
        }

        return $this
            ->httpResponse()
            ->setNextUrl(route('order_returns.edit', $returnRequest->id))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(int|string $id, Request $request)
    {
        $orderReturn = $this->findOrFail($id);

        try {
            $orderReturn->delete();
            event(new DeletedContentEvent(ORDER_RETURN_MODULE_SCREEN_NAME, $request, $orderReturn));

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

    protected function findOrFail(int|string $id): OrderReturn|Model|null
    {
        return OrderReturn::query()
            ->where('id', $id)
            ->where('store_id', $this->getStore()->id)
            ->firstOrFail();
    }
}
