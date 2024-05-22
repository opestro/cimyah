<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Facades\FlashSale as FlashSaleFacade;
use Botble\Ecommerce\Forms\FlashSaleForm;
use Botble\Ecommerce\Http\Requests\FlashSaleRequest;
use Botble\Ecommerce\Models\FlashSale;
use Botble\Ecommerce\Tables\FlashSaleTable;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FlashSaleController extends BaseController
{
    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            if (! FlashSaleFacade::isEnabled()) {
                abort(404);
            }

            return $next($request);
        });
    }

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::flash-sale.name'), route('flash-sale.index'));
    }

    public function index(FlashSaleTable $table)
    {
        $this->pageTitle(trans('plugins/ecommerce::flash-sale.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/ecommerce::flash-sale.create'));

        return FlashSaleForm::create()->renderForm();
    }

    public function store(FlashSaleRequest $request)
    {
        $flashSale = FlashSale::query()->create($request->input());

        event(new CreatedContentEvent(FLASH_SALE_MODULE_SCREEN_NAME, $request, $flashSale));

        $this->storeProducts($request, $flashSale);

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('flash-sale.index'))
            ->setNextUrl(route('flash-sale.edit', $flashSale->id))
            ->withCreatedSuccessMessage();
    }

    protected function storeProducts(FlashSaleRequest $request, FlashSale $flashSale)
    {
        $products = array_filter(explode(',', $request->input('products')));

        $flashSale->products()->detach();

        foreach ($products as $index => $productId) {
            if (! (int) $productId) {
                continue;
            }

            $extra = Arr::get($request->input('products_extra', []), $index);

            if (! $extra || ! isset($extra['price']) || ! isset($extra['quantity'])) {
                continue;
            }

            $extra['price'] = (float) $extra['price'];
            $extra['quantity'] = (int) $extra['quantity'];

            if ($flashSale->products()->where('id', $productId)->count()) {
                $flashSale->products()->sync([(int) $productId => $extra]);
            } else {
                $flashSale->products()->attach($productId, $extra);
            }
        }

        return count($products);
    }

    public function edit(FlashSale $flashSale, Request $request)
    {
        event(new BeforeEditContentEvent($request, $flashSale));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $flashSale->name]));

        return FlashSaleForm::createFromModel($flashSale)->renderForm();
    }

    public function update(FlashSale $flashSale, FlashSaleRequest $request)
    {
        $flashSale->fill($request->input());
        $flashSale->save();

        $this->storeProducts($request, $flashSale);

        event(new UpdatedContentEvent(FLASH_SALE_MODULE_SCREEN_NAME, $request, $flashSale));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('flash-sale.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(FlashSale $flashSale, Request $request)
    {
        try {
            $flashSale->delete();

            event(new DeletedContentEvent(FLASH_SALE_MODULE_SCREEN_NAME, $request, $flashSale));

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
