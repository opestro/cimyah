<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Traits\LocationTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingRuleItem extends BaseModel
{
    use LocationTrait;

    protected $table = 'ec_shipping_rule_items';

    protected $fillable = [
        'shipping_rule_id',
        'country',
        'state',
        'city',
        'adjustment_price',
        'is_enabled',
        'zip_code',
    ];

    public function shippingRule(): BelongsTo
    {
        return $this->belongsTo(ShippingRule::class)->withDefault();
    }

    protected function adjustmentPrice(): Attribute
    {
        return Attribute::set(fn (string $value) => (float) str_replace(',', '', $value));
    }

    protected function nameItem(): Attribute
    {
        return Attribute::get(fn () => trim(implode(', ', array_filter([$this->state_name, $this->city_name, $this->zip_code]))));
    }
}
