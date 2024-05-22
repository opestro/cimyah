<?php

namespace Botble\Ecommerce\Supports;

use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Rules\OnOffRule;
use Botble\Ecommerce\Forms\Settings\FlashSaleSettingForm;
use Botble\Ecommerce\Http\Requests\Settings\FlashSaleSettingRequest;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Botble\Support\Http\Requests\Request;
use Illuminate\Support\Collection;

class FlashSaleSupport
{
    protected Collection|array $flashSales = [];

    public function flashSaleForProduct(Product $product): ?Product
    {
        if (! $this->flashSales) {
            $this->getAvailableFlashSales();
        }

        if (! $product->id) {
            return null;
        }

        foreach ($this->flashSales as $flashSale) {
            $productId = $product->id;
            if ($product->is_variation) {
                $productId = $product->original_product->id;
            }

            foreach ($flashSale->products as $flashSaleProduct) {
                if ($productId == $flashSaleProduct->id) {
                    return $flashSaleProduct;
                }
            }
        }

        return null;
    }

    public function getAvailableFlashSales(): Collection
    {
        if (! $this->flashSales instanceof Collection) {
            $this->flashSales = collect();
        }

        if ($this->flashSales->count() == 0) {
            $this->flashSales = app(FlashSaleInterface::class)->getAvailableFlashSales(['products']);
        }

        return $this->flashSales;
    }

    public function isEnabled(): bool
    {
        return get_ecommerce_setting('flash_sale_enabled', true);
    }

    public function isShowSaleCountLeft(): bool
    {
        return get_ecommerce_setting('flash_sale_show_sale_count_left', true);
    }

    public function addShowSaleCountLeftSetting(): void
    {
        add_filter('core_request_rules', function (array $rules, Request $request) {
            if ($request instanceof FlashSaleSettingRequest) {
                $rules['flash_sale_show_sale_count_left'] = [new OnOffRule()];
            }

            return $rules;
        }, 10, 2);

        FlashSaleSettingForm::extend(function (FlashSaleSettingForm $form) {
            $form->addAfter(
                'open_wrapper',
                'flash_sale_show_sale_count_left',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.flash_sale.show_sale_count_left'))
                    ->helperText(trans('plugins/ecommerce::setting.flash_sale.show_sale_count_left_description'))
                    ->colspan(2)
                    ->value($this->isShowSaleCountLeft())
                    ->toArray()
            );
        });
    }
}
