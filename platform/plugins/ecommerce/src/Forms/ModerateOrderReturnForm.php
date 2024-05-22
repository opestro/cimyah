<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\FormAbstract;

class ModerateOrderReturnForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->contentOnly()
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->toArray())
            ->add(
                'button_wrapper',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content('<div class="d-flex gap-2 justify-content-end">')
                    ->toArray()
            )
            ->add(
                'close',
                'button',
                ButtonFieldOption::make()
                    ->label(trans('core/base::base.close'))
                    ->cssClass('btn')
                    ->addAttribute('data-bs-dismiss', 'modal')
                    ->toArray()
            )
            ->add(
                'button_wrapper_close',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content('</div>')
                    ->toArray()
            );
    }

    public function addHiddenStatus(string $status): self
    {
        return $this->addBefore(
            'description',
            'return_status',
            'hidden',
            TextFieldOption::make()
                ->value($status)
                ->toArray()
        );
    }

    public function addSubmitButton(string $title, string $color): self
    {
        return $this->addAfter(
            'close',
            'submit',
            'submit',
            ButtonFieldOption::make()
                ->label($title)
                ->cssClass("btn btn-$color btn-update-order")
                ->toArray()
        );
    }
}
