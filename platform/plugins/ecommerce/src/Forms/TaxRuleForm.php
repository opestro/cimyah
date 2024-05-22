<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Forms\Concerns\HasSubmitButton;
use Botble\Ecommerce\Http\Requests\TaxRuleRequest;
use Botble\Ecommerce\Models\Tax;
use Botble\Ecommerce\Models\TaxRule;
use Botble\Location\Fields\SelectLocationField;
use Illuminate\Support\Facades\Request;

class TaxRuleForm extends FormAbstract
{
    use HasSubmitButton;

    public function setup(): void
    {
        $this
            ->setupModel(new TaxRule())
            ->setValidatorClass(TaxRuleRequest::class)
            ->setFormOption('id', 'ecommerce-tax-rule-form')
            ->when(Request::ajax(), function (FormAbstract $form) {
                $form->contentOnly();
            });

        if (! $this->getModel()->getKey()) {
            $this
                ->when(
                    $taxId = request()->input('tax_id'),
                    fn (FormAbstract $form) => $form->add('tax_id', 'hidden', [
                        'value' => $taxId,
                    ]),
                    function (FormAbstract $form) {
                        $taxes = Tax::query()->pluck('title', 'id')->toArray();
                        $form
                            ->add('tax_id', 'customSelect', [
                                'label' => trans('plugins/ecommerce::tax.tax'),
                                'choices' => $taxes,
                            ]);
                    }
                );
        }

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            $this->add(
                'location',
                SelectLocationField::class,
                [
                    'locationKeys' => [
                        'country' => 'country',
                        'state' => 'state',
                        'city' => 'city',
                    ],
                ]
            );
        } else {
            $this
                ->add('country', 'customSelect', [
                    'label' => trans('plugins/ecommerce::tax.state'),
                    'attr' => [
                        'data-type' => 'country',
                    ],
                    'choices' => EcommerceHelper::getAvailableCountries(),
                ])
                ->add('state', 'text', [
                    'label' => trans('plugins/ecommerce::tax.state'),
                    'attr' => [
                        'placeholder' => trans('plugins/ecommerce::tax.state'),
                    ],
                ])
                ->add('city', 'text', [
                    'label' => trans('plugins/ecommerce::tax.city'),
                    'attr' => [
                        'placeholder' => trans('plugins/ecommerce::tax.city'),
                    ],
                ]);
        }

        if (EcommerceHelper::isZipCodeEnabled()) {
            $this
                ->add('zip_code', 'text', [
                    'label' => trans('plugins/ecommerce::tax.zip_code'),
                ]);
        }

        if ($this->request->ajax()) {
            $this->addSubmitButton(trans('core/base::forms.save'), 'ti ti-device-floppy', [
                'wrapper' => [
                    'class' => 'd-grid gap-2',
                ],
            ]);
        }
    }
}
