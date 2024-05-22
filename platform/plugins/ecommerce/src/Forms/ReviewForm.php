<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MediaImagesField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Forms\Fronts\Auth\FieldOptions\EmailFieldOption;
use Botble\Ecommerce\Forms\Fronts\Auth\FieldOptions\TextFieldOption;
use Botble\Ecommerce\Http\Requests\ReviewRequest;
use Botble\Ecommerce\Models\Review;
use Carbon\Carbon;

class ReviewForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new Review())
            ->setValidatorClass(ReviewRequest::class)
            ->add(
                'product_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/ecommerce::review.product'))
                    ->ajaxSearch()
                    ->required()
                    ->ajaxUrl(route('reviews.ajax-search-products'))
                    ->toArray()
            )
            ->add(
                'customer_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/ecommerce::review.choose_existing_customer'))
                    ->ajaxSearch()
                    ->ajaxUrl(route('reviews.ajax-search-customers'))
                    ->helperText(trans('plugins/ecommerce::review.choose_customer_help'))
                    ->toArray()
            )
            ->add(
                'open_or',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content(sprintf(
                        '<div class="form-fieldset"><label class="form-label">%s</label>',
                        trans('plugins/ecommerce::review.or_enter_manually')
                    ))
                    ->toArray()
            )
            ->add(
                'customer_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::ecommerce.customer_name'))
                    ->toArray()
            )
            ->add(
                'customer_email',
                EmailField::class,
                EmailFieldOption::make()
                    ->label(trans('plugins/ecommerce::ecommerce.customer_email'))
                    ->toArray()
            )
            ->add(
                'close_or',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content('</div>')
                    ->toArray()
            )
            ->add(
                'star',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/ecommerce::review.star'))
                    ->choices(array_combine(range(1, 5), range(1, 5)))
                    ->selected(5)
                    ->toArray()
            )
            ->add(
                'comment',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('plugins/ecommerce::review.comment'))
                    ->required()
                    ->toArray()
            )
            ->add('images[]', MediaImagesField::class, [
                'label' => trans('plugins/ecommerce::review.images'),
                'values' => $this->model->images,
            ])
            ->add(
                'created_at',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->label(trans('core/base::tables.created_at'))
                    ->value(Carbon::now())
                    ->withTimePicker()
                    ->toArray()
            )
            ->setBreakFieldPoint('created_at');
    }
}
