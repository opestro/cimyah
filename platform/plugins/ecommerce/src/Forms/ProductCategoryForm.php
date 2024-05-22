<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Facades\ProductCategoryHelper;
use Botble\Ecommerce\Http\Requests\ProductCategoryRequest;
use Botble\Ecommerce\Models\ProductCategory;

class ProductCategoryForm extends FormAbstract
{
    public function setup(): void
    {
        $categories = ProductCategoryHelper::getTreeCategoriesOptions(ProductCategoryHelper::getTreeCategories());

        $categories = [0 => trans('plugins/ecommerce::product-categories.none')] + $categories;

        $maxOrder = ProductCategory::query()
            ->whereIn('parent_id', [0, null])
            ->orderByDesc('order')
            ->value('order');

        $this
            ->setupModel(new ProductCategory())
            ->setValidatorClass(ProductCategoryRequest::class)
            ->add('order', 'hidden', [
                'value' => $maxOrder + 1,
            ])
            ->add('name', TextField::class, NameFieldOption::make()->toArray())
            ->add(
                'parent_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('core/base::forms.parent'))
                    ->choices($categories)
                    ->searchable()
                    ->toArray()
            )
            ->add(
                'description',
                EditorField::class,
                ContentFieldOption::make()->label(trans('core/base::forms.description'))->toArray()
            )
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->add('image', MediaImageField::class)
            ->add(
                'icon',
                $this->getFormHelper()->hasCustomField('themeIcon') ? 'themeIcon' : 'text',
                [
                    'label' => __('Font Icon'),
                    'attr' => [
                        'placeholder' => 'ex: fa fa-home',
                    ],
                    'empty_value' => __('-- None --'),
                ]
            )
            ->add('icon_image', MediaImageField::class, [
                'label' => __('Icon image'),
                'help_block' => [
                    'text' => __('It will replace Icon Font if it is present.'),
                ],
                'wrapper' => [
                    'style' => 'display: block;',
                ],
            ])
            ->add(
                'is_featured',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('core/base::forms.is_featured'))
                    ->defaultValue(false)
                    ->toArray()
            )
            ->setBreakFieldPoint('status');
    }
}
