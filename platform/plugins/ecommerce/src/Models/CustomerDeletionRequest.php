<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Enums\DeletionRequestStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerDeletionRequest extends BaseModel
{
    protected $table = 'ec_customer_deletion_requests';

    protected $fillable = [
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'token',
        'status',
        'reason',
        'confirmed_at',
        'deleted_at',
    ];

    protected $casts = [
        'status' => DeletionRequestStatusEnum::class,
        'confirmed_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
