<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Forms\ProductLabelForm;
use Botble\Ecommerce\Http\Requests\ProductLabelRequest;
use Botble\Ecommerce\Models\ProductLabel;
use Botble\Ecommerce\Tables\ProductLabelTable;
use Exception;
use Illuminate\Http\Request;

class ProductLabelController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::product-label.name'), route('product-label.index'));
    }

    public function index(ProductLabelTable $table)
    {
        $this->pageTitle(trans('plugins/ecommerce::product-label.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/ecommerce::product-label.create'));

        return ProductLabelForm::create()->renderForm();
    }

    public function store(ProductLabelRequest $request)
    {
        $productLabel = ProductLabel::query()->create($request->input());

        event(new CreatedContentEvent(PRODUCT_LABEL_MODULE_SCREEN_NAME, $request, $productLabel));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('product-label.index'))
            ->setNextUrl(route('product-label.edit', $productLabel->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(int|string $id, Request $request)
    {
        $productLabel = ProductLabel::query()->findOrFail($id);

        event(new BeforeEditContentEvent($request, $productLabel));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $productLabel->name]));

        return ProductLabelForm::createFromModel($productLabel)->renderForm();
    }

    public function update(int|string $id, ProductLabelRequest $request)
    {
        $productLabel = ProductLabel::query()->findOrFail($id);

        $productLabel->fill($request->input());
        $productLabel->save();

        event(new UpdatedContentEvent(PRODUCT_LABEL_MODULE_SCREEN_NAME, $request, $productLabel));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('product-label.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(int|string $id, Request $request)
    {
        try {
            $productLabel = ProductLabel::query()->findOrFail($id);

            $productLabel->delete();

            event(new DeletedContentEvent(PRODUCT_LABEL_MODULE_SCREEN_NAME, $request, $productLabel));

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
