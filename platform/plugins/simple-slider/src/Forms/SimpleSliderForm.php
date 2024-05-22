<?php

namespace Botble\SimpleSlider\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\SimpleSlider\Http\Requests\SimpleSliderRequest;
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\SimpleSlider\Tables\SimpleSliderItemTable;
use Botble\Table\TableBuilder;

class SimpleSliderForm extends FormAbstract
{
    public function __construct(protected TableBuilder $tableBuilder)
    {
        parent::__construct();
    }

    public function setup(): void
    {
        $this
            ->model(SimpleSlider::class)
            ->setValidatorClass(SimpleSliderRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add(
                'key',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/simple-slider::simple-slider.key'))
                ->required()
                ->maxLength(120)
                ->toArray()
            )
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->toArray())
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->setBreakFieldPoint('status')
            ->when($this->model->id, function () {
                $this->addMetaBoxes([
                    'slider-items' => [
                        'title' => trans('plugins/simple-slider::simple-slider.slide_items'),
                        'content' => $this->tableBuilder->create(SimpleSliderItemTable::class)
                            ->setAjaxUrl(route(
                                'simple-slider-item.index',
                                $this->getModel()->id ?: 0
                            ))
                            ->renderTable([
                                'simple_slider_id' => $this->getModel()->getKey(),
                            ]),
                        'header_actions' => view('plugins/simple-slider::partials.header-actions', [
                            'slider' => $this->getModel(),
                        ])->render(),
                        'has_table' => true,
                    ],
                ]);
            });
    }
}
