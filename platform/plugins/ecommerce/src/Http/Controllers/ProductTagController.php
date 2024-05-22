<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Forms\ProductTagForm;
use Botble\Ecommerce\Http\Requests\ProductTagRequest;
use Botble\Ecommerce\Models\ProductTag;
use Botble\Ecommerce\Tables\ProductTagTable;
use Exception;
use Illuminate\Http\Request;

class ProductTagController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::product-tag.name'), route('product-tag.index'));
    }

    public function index(ProductTagTable $table)
    {
        $this->pageTitle(trans('plugins/ecommerce::product-tag.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/ecommerce::product-tag.create'));

        return ProductTagForm::create()->renderForm();
    }

    public function store(ProductTagRequest $request)
    {
        $productTag = ProductTag::query()->create($request->input());

        event(new CreatedContentEvent(PRODUCT_TAG_MODULE_SCREEN_NAME, $request, $productTag));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('product-tag.index'))
            ->setNextUrl(route('product-tag.edit', $productTag->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(int|string $id, Request $request)
    {
        $productTag = ProductTag::query()->findOrFail($id);

        event(new BeforeEditContentEvent($request, $productTag));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $productTag->name]));

        return ProductTagForm::createFromModel($productTag)->renderForm();
    }

    public function update(int|string $id, ProductTagRequest $request)
    {
        $productTag = ProductTag::query()->findOrFail($id);

        $productTag->fill($request->input());
        $productTag->save();

        event(new UpdatedContentEvent(PRODUCT_TAG_MODULE_SCREEN_NAME, $request, $productTag));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('product-tag.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(int|string $id, Request $request)
    {
        try {
            $productTag = ProductTag::query()->findOrFail($id);

            $productTag->delete();

            event(new DeletedContentEvent(PRODUCT_TAG_MODULE_SCREEN_NAME, $request, $productTag));

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

    public function getAllTags()
    {
        return ProductTag::query()->pluck('name')->all();
    }
}
