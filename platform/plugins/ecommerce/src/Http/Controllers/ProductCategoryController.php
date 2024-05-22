<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Http\Requests\UpdateTreeCategoryRequest;
use Botble\Ecommerce\Forms\ProductCategoryForm;
use Botble\Ecommerce\Http\Requests\ProductCategoryRequest;
use Botble\Ecommerce\Http\Resources\ProductCategoryResource;
use Botble\Ecommerce\Models\ProductCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends BaseController
{
    public function index(Request $request)
    {
        $this->pageTitle(trans('plugins/ecommerce::product-categories.name'));

        $categories = ProductCategory::query()
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->with('slugable')
            ->withCount('products')
            ->get();

        if ($request->ajax()) {
            $data = view('core/base::forms.partials.tree-categories', $this->getOptions(compact('categories')))
                ->render();

            return $this
                ->httpResponse()
                ->setData($data);
        }

        Assets::addStylesDirectly(['vendor/core/core/base/css/tree-category.css'])
            ->addScriptsDirectly(['vendor/core/core/base/js/tree-category.js']);

        $form = ProductCategoryForm::create(['template' => 'core/base::forms.form-tree-category']);
        $form = $this->setFormOptions($form, null, compact('categories'));

        return $form->renderForm();
    }

    public function create(Request $request)
    {
        $this->pageTitle(trans('plugins/ecommerce::product-categories.create'));

        if ($request->ajax()) {
            return $this
                ->httpResponse()
                ->setData($this->getForm());
        }

        return ProductCategoryForm::create()->renderForm();
    }

    public function store(ProductCategoryRequest $request)
    {
        $productCategory = ProductCategory::query()->create($request->input());

        event(new CreatedContentEvent(PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $request, $productCategory));

        $response = $this->httpResponse();

        if ($request->ajax()) {
            /**
             * @var ProductCategory $productCategory
             */
            $productCategory = ProductCategory::query()->findOrFail($productCategory->id);

            if ($response->isSaving()) {
                $form = $this->getForm();
            } else {
                $form = $this->getForm($productCategory);
            }

            $response->setData([
                'model' => $productCategory,
                'form' => $form,
            ]);
        }

        return $response
            ->setPreviousUrl(route('product-categories.index'))
            ->setNextUrl(route('product-categories.edit', $productCategory->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(ProductCategory $productCategory, Request $request)
    {
        if ($request->ajax()) {
            return $this
                ->httpResponse()
                ->setData($this->getForm($productCategory));
        }

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $productCategory->name]));

        return ProductCategoryForm::createFromModel($productCategory)->renderForm();
    }

    public function update(ProductCategory $productCategory, ProductCategoryRequest $request)
    {
        $productCategory->fill($request->input());
        $productCategory->save();

        event(new UpdatedContentEvent(PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $request, $productCategory));

        $response = $this->httpResponse();

        if ($request->ajax()) {
            if ($response->isSaving()) {
                $form = $this->getForm();
            } else {
                $form = $this->getForm($productCategory);
            }

            $response->setData([
                'model' => $productCategory,
                'form' => $form,
            ]);
        }

        return $response
            ->setPreviousUrl(route('product-categories.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(ProductCategory $productCategory, Request $request)
    {
        try {
            $productCategory->delete();
            event(new DeletedContentEvent(PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $request, $productCategory));

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

    public function updateTree(UpdateTreeCategoryRequest $request)
    {
        ProductCategory::updateTree($request->validated('data'));

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }

    protected function getForm(?ProductCategory $model = null): string
    {
        $options = ['template' => 'core/base::forms.form-no-wrap'];
        if ($model) {
            $options['model'] = $model;
        }

        $form = ProductCategoryForm::create($options);

        $form = $this->setFormOptions($form, $model);

        return $form->renderForm();
    }

    protected function setFormOptions(FormAbstract $form, ?ProductCategory $model = null, array $options = [])
    {
        if (! $model) {
            $form->setUrl(route('product-categories.create'));
        }

        if (! Auth::user()->hasPermission('product-categories.create') && ! $model) {
            $class = $form->getFormOption('class');
            $form->setFormOption('class', $class . ' d-none');
        }

        $form->setFormOptions($this->getOptions($options));

        return $form;
    }

    protected function getOptions(array $options = []): array
    {
        return array_merge([
            'canCreate' => Auth::user()->hasPermission('product-categories.create'),
            'canEdit' => Auth::user()->hasPermission('product-categories.edit'),
            'canDelete' => Auth::user()->hasPermission('product-categories.destroy'),
            'createRoute' => 'product-categories.create',
            'editRoute' => 'product-categories.edit',
            'deleteRoute' => 'product-categories.destroy',
            'updateTreeRoute' => 'product-categories.update-tree',
        ], $options);
    }

    public function getSearch(Request $request)
    {
        $term = $request->input('search');

        $categories = ProductCategory::query()
            ->select(['id', 'name'])
            ->where('name', 'LIKE', '%' . $term . '%')
            ->paginate(10);

        $data = ProductCategoryResource::collection($categories);

        return $this
            ->httpResponse()
            ->setData($data)->toApiResponse();
    }

    public function getListForSelect()
    {
        $categories = ProductCategory::query()
            ->toBase()
            ->select([
                'id',
                'name',
                'parent_id',
            ])
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->get();

        return $this
            ->httpResponse()
            ->setData($this->buildTree($categories->groupBy('parent_id')));
    }

    protected function buildTree(
        Collection $categories,
        ?Collection $tree = null,
        int|string $parentId = 0,
        ?string $indent = null
    ): Collection {
        if ($tree === null) {
            $tree = collect();
        }

        $currentCategories = $categories->get($parentId);

        if ($currentCategories) {
            foreach ($currentCategories as $category) {
                $tree->push([
                    'id' => $category->id,
                    'name' => $indent . ' ' . $category->name,
                ]);

                if ($categories->has($category->id)) {
                    $this->buildTree($categories, $tree, $category->id, $indent . '&nbsp;&nbsp;');
                }
            }
        }

        return $tree;
    }
}
