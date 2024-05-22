<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Enums\OrderReturnStatusEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\OrderReturnHelper;
use Botble\Ecommerce\Http\Requests\UpdateOrderReturnRequest;
use Botble\Ecommerce\Models\OrderReturn;
use Botble\Ecommerce\Tables\OrderReturnTable;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class OrderReturnController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::order.order_return'), route('order_returns.index'));
    }

    public function index(OrderReturnTable $orderReturnTable)
    {
        $this->pageTitle(trans('plugins/ecommerce::order.order_return'));

        return $orderReturnTable->renderTable();
    }

    public function edit(OrderReturn $orderReturn)
    {
        Assets::addStylesDirectly('vendor/core/plugins/ecommerce/css/ecommerce.css')
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/libraries/jquery.textarea_autosize.js',
                'vendor/core/plugins/ecommerce/js/order.js',
            ])
            ->addScripts('input-mask');

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');
        }

        $this->pageTitle(trans('plugins/ecommerce::order.edit_order_return', ['code' => $orderReturn->code]));

        $defaultStore = get_primary_store_locator();

        $orderReturn->loadMissing(['histories' => fn (HasMany $query) => $query->latest()]);

        return view(
            'plugins/ecommerce::order-returns.edit',
            ['returnRequest' => $orderReturn, 'defaultStore' => $defaultStore]
        );
    }

    public function update(OrderReturn $orderReturn, UpdateOrderReturnRequest $request)
    {
        if (in_array($orderReturn->return_status, [$request->input('return_status'), OrderReturnStatusEnum::CANCELED, OrderReturnStatusEnum::COMPLETED])) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/ecommerce::order.notices.update_return_order_status_error'));
        }

        [$status, $orderReturn] = OrderReturnHelper::updateReturnOrder($orderReturn, $request->validated());

        if (! $status) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/ecommerce::order.notices.update_return_order_status_error'));
        }

        return $this
            ->httpResponse()
            ->setNextUrl(route('order_returns.edit', $orderReturn->getKey()))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(OrderReturn $orderReturn, Request $request)
    {
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
}
