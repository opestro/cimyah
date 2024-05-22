<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Traits\LocationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRule extends BaseModel
{
    use LocationTrait;

    protected $table = 'ec_tax_rules';

    protected $fillable = [
        'tax_id',
        'country',
        'state',
        'city',
        'zip_code',
        'priority',
        'is_enabled',
    ];

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class)->withDefault();
    }
}
