<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Forms\ProductAttributeSetForm;
use Botble\Ecommerce\Http\Requests\ProductAttributeSetsRequest;
use Botble\Ecommerce\Models\ProductAttributeSet;
use Botble\Ecommerce\Services\ProductAttributes\StoreAttributeSetService;
use Botble\Ecommerce\Tables\ProductAttributeSetsTable;

class ProductAttributeSetsController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::product-attributes.name'), route('product-attribute-sets.index'));
    }

    public function index(ProductAttributeSetsTable $dataTable)
    {
        $this->pageTitle(trans('plugins/ecommerce::product-attributes.name'));

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/ecommerce::product-attributes.create'));

        return ProductAttributeSetForm::create()->renderForm();
    }

    public function store(ProductAttributeSetsRequest $request, StoreAttributeSetService $service)
    {
        $productAttributeSet = $service->execute($request, new ProductAttributeSet());

        $productAttributeSet->categories()->detach();

        $productAttributeSet->categories()->sync((array) $request->input('categories', []));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('product-attribute-sets.index'))
            ->setNextUrl(route('product-attribute-sets.edit', $productAttributeSet->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(ProductAttributeSet $productAttributeSet)
    {
        $this->pageTitle(trans('plugins/ecommerce::product-attributes.edit'));

        return ProductAttributeSetForm::createFromModel($productAttributeSet)
            ->renderForm();
    }

    public function update(
        ProductAttributeSet $productAttributeSet,
        ProductAttributeSetsRequest $request,
        StoreAttributeSetService $service,
    ) {
        $service->execute($request, $productAttributeSet);

        $productAttributeSet->categories()->detach();

        $productAttributeSet->categories()->sync((array) $request->input('categories', []));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('product-attribute-sets.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(ProductAttributeSet $productAttributeSet)
    {
        return DeleteResourceAction::make($productAttributeSet);
    }
}
