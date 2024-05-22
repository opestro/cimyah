<?php

namespace Botble\Location\Forms;

use Botble\Base\Facades\Assets;
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
use Botble\Location\Http\Requests\CityRequest;
use Botble\Location\Models\City;
use Botble\Location\Models\Country;

class CityForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');

        $countries = Country::query()->pluck('name', 'id')->all();

        $states = [];
        if ($this->getModel()) {
            $states = $this->getModel()->country->states()->pluck('name', 'id')->all();
        }

        $this
            ->model(City::class)
            ->setValidatorClass(CityRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add('slug', TextField::class, [
                'label' => __('Slug'),
                'attr' => [
                    'placeholder' => __('Slug'),
                    'data-counter' => 120,
                ],
            ])
            ->add('country_id', SelectField::class, [
                'label' => trans('plugins/location::city.country'),
                'required' => true,
                'attr' => [
                    'id' => 'country_id',
                    'class' => 'select-search-full',
                    'data-type' => 'country',
                ],
                'choices' => [0 => trans('plugins/location::city.select_country')] + $countries,
            ])
            ->add('state_id', SelectField::class, [
                'label' => trans('plugins/location::city.state'),
                'attr' => [
                    'id' => 'state_id',
                    'data-url' => route('ajax.states-by-country'),
                    'class' => 'select-search-full',
                    'data-type' => 'state',
                ],
                'choices' => ($this->getModel()->state_id ?
                        [
                            0 => trans('plugins/location::city.select_state'),
                            $this->model->state->id => $this->model->state->name,
                        ]
                        :
                        [0 => trans('plugins/location::city.select_state')]) + $states,
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make()->toArray())
            ->add('is_default', OnOffField::class, IsDefaultFieldOption::make()->toArray())
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->add('image', MediaImageField::class)
            ->setBreakFieldPoint('status');
    }
}
