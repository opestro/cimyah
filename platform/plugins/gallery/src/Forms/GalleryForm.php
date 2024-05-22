<?php

namespace Botble\Gallery\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Gallery\Http\Requests\GalleryRequest;
use Botble\Gallery\Models\Gallery;

class GalleryForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Gallery::class)
            ->setValidatorClass(GalleryRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->required()->toArray())
            ->add('order', NumberField::class, SortOrderFieldOption::make()->toArray())
            ->add('is_featured', OnOffField::class, OnOffFieldOption::make()->label(trans('core/base::forms.is_featured'))->defaultValue(false)->toArray())
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->add('image', MediaImageField::class)
            ->setBreakFieldPoint('status');
    }
}
