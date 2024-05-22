<?php

namespace Botble\Contact\Forms\Fronts;

use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\InputFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Contact\Enums\CustomFieldType;
use Botble\Contact\Http\Requests\ContactRequest;
use Botble\Contact\Models\Contact;
use Botble\Contact\Models\CustomField;
use Botble\Theme\FormFront;
use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class ContactForm extends FormFront
{
    protected string $errorBag = 'contact';

    protected ?string $formInputWrapperClass = 'contact-form-group';

    protected ?string $formInputClass = 'contact-form-input';

    public static function formTitle(): string
    {
        return trans('plugins/contact::contact.contact_form');
    }

    public function setup(): void
    {
        $data = $this->getModel();

        $displayFields = array_filter(explode(',', (string) Arr::get($data, 'display_fields'))) ?: ['phone', 'email', 'address', 'subject'];
        $mandatoryFields = array_filter(explode(',', (string) Arr::get($data, 'mandatory_fields'))) ?: ['email'];

        $this
            ->contentOnly()
            ->model(Contact::class)
            ->setUrl(route('public.send.contact'))
            ->setValidatorClass(ContactRequest::class)
            ->setFormOption('class', 'contact-form')
            ->add(
                'filters_before_form',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content(apply_filters('pre_contact_form', null))
                    ->toArray()
            )
            ->add(
                'required_fields',
                'hidden',
                TextFieldOption::make()
                    ->value(Arr::get($data, 'mandatory_fields'))
                    ->toArray()
            )
            ->add(
                'display_fields',
                'hidden',
                TextFieldOption::make()
                    ->value(Arr::get($data, 'display_fields'))
                    ->toArray()
            )
            ->addRowWrapper('form_wrapper', function (self $form) use ($displayFields, $mandatoryFields) {
                $customFields = CustomField::query()
                    ->wherePublished()->with('options')
                    ->orderBy('order')
                    ->get();

                $form
                    ->addColumnWrapper('name_wrapper', function (self $form) {
                        $form
                            ->add(
                                'name',
                                TextField::class,
                                TextFieldOption::make()
                                    ->required()
                                    ->label(__('Name'))
                                    ->placeholder(__('Your Name'))
                                    ->wrapperAttributes(['class' => $this->formInputWrapperClass])
                                    ->cssClass($this->formInputClass)
                                    ->maxLength(-1)
                                    ->toArray()
                            );
                    })
                    ->when(in_array('email', $displayFields), function (self $form) use ($mandatoryFields) {
                        $form
                            ->addColumnWrapper('email_wrapper', function (self $form) use ($mandatoryFields) {
                                $form
                                    ->add(
                                        'email',
                                        EmailField::class,
                                        TextFieldOption::make()
                                            ->when(in_array('email', $mandatoryFields), function (TextFieldOption $option) {
                                                $option->required();
                                            })
                                            ->label(__('Email'))
                                            ->placeholder(__('Your Email'))
                                            ->wrapperAttributes(['class' => $this->formInputWrapperClass])
                                            ->cssClass($this->formInputClass)
                                            ->maxLength(-1)
                                            ->toArray()
                                    );
                            });
                    })
                    ->when(in_array('address', $displayFields), function (self $form) use ($mandatoryFields) {
                        $form->addColumnWrapper('address_wrapper', function (self $form) use ($mandatoryFields) {
                            $form
                                ->add(
                                    'address',
                                    TextField::class,
                                    TextFieldOption::make()
                                        ->when(in_array('address', $mandatoryFields), function (TextFieldOption $option) {
                                            $option->required();
                                        })
                                        ->label(__('Address'))
                                        ->placeholder(__('Your Address'))
                                        ->wrapperAttributes(['class' => $this->formInputWrapperClass])
                                        ->cssClass($this->formInputClass)
                                        ->maxLength(-1)
                                        ->toArray()
                                );
                        });
                    })
                    ->when(in_array('phone', $displayFields), function (self $form) use ($mandatoryFields) {
                        $form->addColumnWrapper('phone_wrapper', function (self $form) use ($mandatoryFields) {
                            $form
                                ->add(
                                    'phone',
                                    TextField::class,
                                    TextFieldOption::make()
                                        ->when(in_array('phone', $mandatoryFields), function (TextFieldOption $option) {
                                            $option->required();
                                        })
                                        ->label(__('Phone'))
                                        ->placeholder(__('Your Phone'))
                                        ->wrapperAttributes(['class' => $this->formInputWrapperClass])
                                        ->cssClass($this->formInputClass)
                                        ->maxLength(-1)
                                        ->toArray()
                                );
                        });
                    })
                    ->when(in_array('subject', $displayFields), function (self $form) use ($mandatoryFields) {
                        $form->addColumnWrapper('subject_wrapper', function (self $form) use ($mandatoryFields) {
                            $form->add(
                                'subject',
                                TextField::class,
                                TextFieldOption::make()
                                    ->when(in_array('subject', $mandatoryFields), function (TextFieldOption $option) {
                                        $option->required();
                                    })
                                    ->label(__('Subject'))
                                    ->placeholder(__('Subject'))
                                    ->wrapperAttributes(['class' => $this->formInputWrapperClass])
                                    ->cssClass($this->formInputClass)
                                    ->maxLength(-1)
                                    ->toArray()
                            );
                        }, 12);
                    })
                    ->when($customFields, function (ContactForm $form, Collection $customFields) {
                        foreach ($customFields as $customField) {
                            $options = $customField->options->pluck('label', 'value')->all();

                            $fieldOptions = match ($customField->type->getValue()) {
                                CustomFieldType::NUMBER => NumberFieldOption::make()
                                    ->when($customField->placeholder, function (InputFieldOption $options, string $placeholder) {
                                        $options->placeholder($placeholder);
                                    }),
                                CustomFieldType::DROPDOWN => SelectFieldOption::make()
                                    ->when($customField->placeholder, function (SelectFieldOption $fieldOptions, string $placeholder) use ($options) {
                                        $fieldOptions->choices(['' => $placeholder, ...$options]);
                                    }, function (SelectFieldOption $fieldOptions) use ($options) {
                                        $fieldOptions->choices($options);
                                    }),
                                CustomFieldType::CHECKBOX => CheckboxFieldOption::make(),
                                CustomFieldType::RADIO => RadioFieldOption::make()
                                    ->choices($options),
                                default => TextFieldOption::make()
                                    ->wrapperAttributes(['class' => $this->formInputWrapperClass])
                                    ->cssClass($this->formInputClass)
                                    ->when($customField->placeholder, function (InputFieldOption $options, string $placeholder) {
                                        $options->placeholder($placeholder);
                                    }),
                            };

                            $field = match ($customField->type->getValue()) {
                                CustomFieldType::NUMBER => NumberField::class,
                                CustomFieldType::TEXTAREA => TextareaField::class,
                                CustomFieldType::DROPDOWN => SelectField::class,
                                CustomFieldType::CHECKBOX => OnOffCheckboxField::class,
                                CustomFieldType::RADIO => RadioField::class,
                                default => TextField::class,
                            };

                            $form->addColumnWrapper("custom_field_{$customField->id}_wrapper", function (self $form) use ($customField, $field, $fieldOptions) {
                                $form->add(
                                    "contact_custom_fields[$customField->id]",
                                    $field,
                                    $fieldOptions
                                        ->label($customField->name)
                                        ->required($customField->required)
                                );
                            }, 12);
                        }
                    });
            })
            ->addRowWrapper(
                'content',
                function (self $form) {
                    $form->addColumnWrapper(
                        'content',
                        function (self $form) {
                            $form->add(
                                'content',
                                TextareaField::class,
                                TextareaFieldOption::make()
                                    ->required()
                                    ->label(__('Content'))
                                    ->placeholder(__('Write your message here'))
                                    ->wrapperAttributes(['class' => $this->formInputWrapperClass])
                                    ->cssClass($this->formInputClass)
                                    ->rows(5)
                                    ->maxLength(-1)
                                    ->toArray()
                            );
                        },
                        12
                    );
                }
            )
            ->add(
                'filters_after_form',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content(apply_filters('after_contact_form', null))
                    ->toArray()
            )
            ->addWrappedField(
                'submit',
                'submit',
                ButtonFieldOption::make()
                    ->cssClass('contact-button')
                    ->label(__('Send'))
                    ->toArray()
            )
            ->addWrappedField(
                'messages',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content(<<<'HTML'
                        <div class="contact-message contact-success-message" style="display: none"></div>
                        <div class="contact-message contact-error-message" style="display: none"></div>
                    HTML)
                    ->toArray()
            );
    }

    protected function addWrappedField(string $name, string $type, array $options): static
    {
        $this->add(
            "open_{$name}_field_wrapper",
            HtmlField::class,
            HtmlFieldOption::make()->content('<div class="contact-form-group">')->toArray()
        );

        $this->add($name, $type, $options);

        return $this->add(
            "close_{$name}_field_wrapper",
            HtmlField::class,
            HtmlFieldOption::make()->content('</div>')->toArray()
        );
    }

    protected function addRowWrapper(string $name, Closure $callback): static
    {
        $this->add(
            "open_{$name}_row_wrapper",
            HtmlField::class,
            HtmlFieldOption::make()->content('<div class="contact-form-row row">')->toArray()
        );

        $callback($this);

        return $this->add(
            "close_{$name}_row_wrapper",
            HtmlField::class,
            HtmlFieldOption::make()->content('</div>')->toArray()
        );
    }

    protected function addColumnWrapper(string $name, Closure $callback, int $column = 6): static
    {
        $this->add(
            "open_{$name}_column_wrapper",
            HtmlField::class,
            HtmlFieldOption::make()->content(sprintf('<div class="contact-column-%s col-md-%s contact-field-%s">', $column, $column, $name))->toArray()
        );

        $callback($this);

        return $this->add(
            "close_{$name}_column_wrapper",
            HtmlField::class,
            HtmlFieldOption::make()->content('</div>')->toArray()
        );
    }
}
