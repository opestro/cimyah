<?php

namespace Botble\Location\Forms;

use Botble\Base\Forms\FieldOptions\IsDefaultFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Location\Http\Requests\StateRequest;
use Botble\Location\Models\Country;
use Botble\Location\Models\State;

class StateForm extends FormAbstract
{
    public function setup(): void
    {
        $countries = Country::query()->pluck('name', 'id')->all();

        $this
            ->model(State::class)
            ->setValidatorClass(StateRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add('slug', TextField::class, [
                'label' => __('Slug'),
                'attr' => [
                    'placeholder' => __('Slug'),
                    'data-counter' => 120,
                ],
            ])
            ->add('abbreviation', TextField::class, [
                'label' => trans('plugins/location::location.abbreviation'),
                'attr' => [
                    'placeholder' => trans('plugins/location::location.abbreviation_placeholder'),
                    'data-counter' => 10,
                ],
            ])
            ->add('country_id', SelectField::class, [
                'label' => trans('plugins/location::state.country'),
                'required' => true,
                'attr' => [
                    'class' => 'select-search-full',
                ],
                'choices' => [0 => trans('plugins/location::state.select_country')] + $countries,
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make()->toArray())
            ->add('is_default', OnOffField::class, IsDefaultFieldOption::make()->toArray())
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->add('image', MediaImageField::class)
            ->setBreakFieldPoint('status');
    }
}
