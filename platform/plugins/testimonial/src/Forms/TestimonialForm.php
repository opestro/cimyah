<?php

namespace Botble\Testimonial\Forms;

use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Testimonial\Http\Requests\TestimonialRequest;
use Botble\Testimonial\Models\Testimonial;

class TestimonialForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Testimonial::class)
            ->setValidatorClass(TestimonialRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add(
                'company',
                TextField::class,
                TextFieldOption::make()->label(trans('plugins/testimonial::testimonial.company'))->maxLength(
                    120
                )->toArray()
            )
            ->add('content', EditorField::class, ContentFieldOption::make()->required()->toArray())
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->add('image', MediaImageField::class)
            ->setBreakFieldPoint('status');
    }
}
