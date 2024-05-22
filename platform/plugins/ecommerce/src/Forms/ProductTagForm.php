<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Http\Requests\ProductTagRequest;
use Botble\Ecommerce\Models\ProductTag;

class ProductTagForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new ProductTag())
            ->setValidatorClass(ProductTagRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->toArray())
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->toArray())
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->setBreakFieldPoint('status');
    }
}
