<?php

namespace Botble\SimpleSlider\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\SimpleSlider\Http\Requests\SimpleSliderItemRequest;
use Botble\SimpleSlider\Models\SimpleSliderItem;

class SimpleSliderItemForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(SimpleSliderItem::class)
            ->setValidatorClass(SimpleSliderItemRequest::class)
            ->contentOnly()
            ->add('simple_slider_id', 'hidden', [
                'value' => $this->getRequest()->input('simple_slider_id'),
            ])
            ->add('title', TextField::class, [
                'label' => trans('core/base::forms.title'),
                'attr' => [
                    'data-counter' => 120,
                ],
            ])
            ->add('link', TextField::class, [
                'label' => trans('core/base::forms.link'),
                'attr' => [
                    'placeholder' => 'https://',
                    'data-counter' => 120,
                ],
            ])
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->toArray())
            ->add('order', NumberField::class, SortOrderFieldOption::make()->toArray())
            ->add('image', MediaImageField::class, MediaImageFieldOption::make()->required()->toArray());
    }
}
