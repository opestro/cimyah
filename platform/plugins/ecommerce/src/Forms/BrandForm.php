<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\TreeCategoryField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Facades\ProductCategoryHelper;
use Botble\Ecommerce\Http\Requests\BrandRequest;
use Botble\Ecommerce\Models\Brand;

class BrandForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new Brand())
            ->setValidatorClass(BrandRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->toArray())
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->toArray())
            ->add('website', 'text', [
                'label' => trans('plugins/ecommerce::brands.form.website'),
                'attr' => [
                    'placeholder' => 'Ex: https://example.com',
                    'data-counter' => 120,
                ],
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make()->toArray())
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->add('logo', MediaImageField::class, MediaImageFieldOption::make()->label(trans('plugins/ecommerce::brands.logo'))->toArray())
            ->add(
                'is_featured',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('core/base::forms.is_featured'))
                    ->defaultValue(false)
                    ->toArray()
            )
            ->add(
                'categories[]',
                TreeCategoryField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/ecommerce::products.form.categories'))
                    ->choices(ProductCategoryHelper::getActiveTreeCategories())
                    ->selected($this->getModel()->getKey() ? $this->getModel()->categories->pluck('id')->all() : [])
                    ->addAttribute('card-body-class', 'p-0')
                    ->toArray()
            )
            ->setBreakFieldPoint('status');
    }
}
