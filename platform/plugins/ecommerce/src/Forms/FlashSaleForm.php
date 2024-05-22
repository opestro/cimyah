<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Http\Requests\FlashSaleRequest;
use Botble\Ecommerce\Models\FlashSale;
use Carbon\Carbon;

class FlashSaleForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/flash-sale.js')
            ->addStylesDirectly(['vendor/core/plugins/ecommerce/css/ecommerce.css'])
            ->addScripts(['input-mask']);

        $this
            ->setupModel(new FlashSale())
            ->setValidatorClass(FlashSaleRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->toArray())
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->add(
                'end_date',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->label(__('End date'))
                    ->required()
                    ->defaultValue(BaseHelper::formatDate(Carbon::now()->addMonth()))
                    ->toArray()
            )
            ->addMetaBoxes([
                'products' => [
                    'title' => trans('plugins/ecommerce::flash-sale.products'),
                    'content' => view('plugins/ecommerce::flash-sales.products', [
                        'flashSale' => $this->getModel(),
                        'products' => $this->getModel()->getKey() ? $this->getModel()->products : collect(),
                    ]),
                    'priority' => 0,
                ],
            ])
            ->setBreakFieldPoint('status');
    }
}
