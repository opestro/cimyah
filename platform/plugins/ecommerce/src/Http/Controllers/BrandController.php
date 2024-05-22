<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Forms\BrandForm;
use Botble\Ecommerce\Http\Requests\BrandRequest;
use Botble\Ecommerce\Http\Resources\BrandResource;
use Botble\Ecommerce\Models\Brand;
use Botble\Ecommerce\Tables\BrandTable;
use Exception;
use Illuminate\Http\Request;

class BrandController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::brands.menu'), route('brands.index'));
    }

    public function index(BrandTable $dataTable)
    {
        $this->pageTitle(trans('plugins/ecommerce::brands.menu'));

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/ecommerce::brands.create'));

        return BrandForm::create()->renderForm();
    }

    public function store(BrandRequest $request)
    {
        /**
         * @var Brand $brand
         */
        $brand = Brand::query()->create($request->input());

        $brand->categories()->detach();

        $brand->categories()->sync((array) $request->input('categories', []));

        event(new CreatedContentEvent(BRAND_MODULE_SCREEN_NAME, $request, $brand));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('brands.index'))
            ->setNextUrl(route('brands.edit', $brand->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(Brand $brand)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $brand->name]));

        return BrandForm::createFromModel($brand)->renderForm();
    }

    public function update(Brand $brand, BrandRequest $request)
    {
        $brand->fill($request->input());
        $brand->save();

        $brand->categories()->detach();

        $brand->categories()->sync((array) $request->input('categories', []));

        event(new UpdatedContentEvent(BRAND_MODULE_SCREEN_NAME, $request, $brand));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('brands.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Brand $brand, Request $request)
    {
        try {
            $brand->delete();

            event(new DeletedContentEvent(BRAND_MODULE_SCREEN_NAME, $request, $brand));

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

    public function getSearch(Request $request)
    {
        $term = $request->input('search');

        $categories = Brand::query()
            ->select(['id', 'name'])
            ->where('name', 'LIKE', '%' . $term . '%')
            ->paginate(10);

        $data = BrandResource::collection($categories);

        return $this
            ->httpResponse()
            ->setData($data)->toApiResponse();
    }
}
