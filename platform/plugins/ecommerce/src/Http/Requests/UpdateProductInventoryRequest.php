<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class UpdateProductInventoryRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'column' => ['required', 'in:with_storehouse_management,quantity,stock_status'],
        ];

        if ($this->input('column') === 'with_storehouse_management') {
            $rules['value'] = [new OnOffRule()];
        } elseif ($this->input('column') === 'quantity') {
            $rules['value'] = ['required', 'numeric'];
        } else {
            $rules['value'] = ['required', 'in:in_stock,out_of_stock,on_backorder'];
        }

        return $rules;
    }
}
