<?php

namespace Botble\Theme;

use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\PasswordField;
use Botble\Base\Forms\Fields\PhoneNumberField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Illuminate\Support\Str;

abstract class FormFront extends FormAbstract
{
    protected ?string $formEndKey = null;

    protected ?string $formInputWrapperClass = null;

    protected ?string $formInputClass = null;

    protected ?string $formLabelClass = null;

    public static function formTitle(): string
    {
        return Str::title(Str::snake(class_basename(static::class), ' '));
    }

    public function buildForm(): void
    {
        $this->add(
            'form_front_form_start',
            HtmlField::class,
            HtmlFieldOption::make()
                ->content(apply_filters('form_front_form_start', '', $this))
                ->toArray()
        );

        parent::buildForm();

        $this->add(
            'form_front_form_end',
            HtmlField::class,
            HtmlFieldOption::make()
                ->content(apply_filters('form_front_form_end', '', $this))
                ->toArray()
        );

        $this->addBefore(
            'submit',
            'form_front_before_submit_button',
            HtmlField::class,
            HtmlFieldOption::make()
                ->content(apply_filters('form_front_before_submit_button', '', $this))
                ->toArray()
        );
    }

    public function setFormEndKey(string $key): static
    {
        $this->formEndKey = $key;

        return $this;
    }

    public function getFormEndKey(): ?string
    {
        return $this->formEndKey;
    }

    public function setFormInputClass(string $class): static
    {
        $this->formInputClass = $class;

        return $this;
    }

    public function setFormInputWrapperClass(string $class): static
    {
        $this->formInputWrapperClass = $class;

        return $this;
    }

    public function setFormLabelClass(string $class): static
    {
        $this->formLabelClass = $class;

        return $this;
    }

    public function renderForm(array $options = [], bool $showStart = true, bool $showFields = true, bool $showEnd = true): string
    {
        foreach ($this->getFields() as &$field) {
            if (! in_array($field->getType(), [
                TextField::class,
                TextareaField::class,
                EmailField::class,
                PasswordField::class,
                PhoneNumberField::class,
                NumberField::class,
                SelectField::class,
                RadioField::class,
                OnOffCheckboxField::class,
            ])) {
                continue;
            }

            if ($this->formInputWrapperClass) {
                $field->setOption('wrapper.class', $this->formInputWrapperClass);
            }

            if ($this->formInputClass) {
                $field->setOption('attr.class', $this->formInputClass);
            }

            if ($this->formLabelClass) {

                $field->setOption('label_attr.class', $this->formLabelClass . str_replace('form-label', '', $field->getOption('label_attr.class', '')));
            }
        }

        return parent::renderForm($options, $showStart, $showFields, $showEnd);
    }
}
