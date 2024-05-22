<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Base\Facades\BaseHelper;
use Botble\Support\Http\Requests\Request;

class ShipmentRequest extends Request
{
    public function rules(): array
    {
        return [
            'tracking_id' => ['nullable', 'max:120'],
            'shipping_company_name' => ['nullable', 'string', 'max:120'],
            'tracking_link' => ['nullable', 'url', 'max:190'],
            'estimate_date_shipped' => 'nullable|date|date_format:' . BaseHelper::getDateFormat(),
            'note' => ['nullable', 'string', 'max:120'],
        ];
    }
}
