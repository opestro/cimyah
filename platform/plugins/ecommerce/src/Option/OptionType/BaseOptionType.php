<?php

namespace Botble\Ecommerce\Option\OptionType;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Option;
use Botble\Ecommerce\Models\Product;

abstract class BaseOptionType
{
    public Option|array|null $option = null;

    public ?Product $product = null;

    public function setOption($option): self
    {
        $this->option = $option;

        return $this;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    abstract public function view(): string;

    public function render(): string
    {
        return view(EcommerceHelper::viewPath('options.' . $this->view()), ['option' => $this->option, 'product' => $this->product])->render();
    }
}
