<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Http\Requests\ProductLabelRequest;
use Botble\Ecommerce\Models\ProductLabel;

class ProductLabelForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new ProductLabel())
            ->setValidatorClass(ProductLabelRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->toArray())
            ->add('color', 'customColor', [
                'label' => trans('plugins/ecommerce::product-label.color'),
                'attr' => [
                    'placeholder' => trans('plugins/ecommerce::product-label.color_placeholder'),
                ],
            ])
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->setBreakFieldPoint('status');
    }
}
