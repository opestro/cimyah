<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GlobalOption extends BaseModel
{
    protected $table = 'ec_global_options';

    protected $fillable = [
        'name',
        'option_type',
        'required',
    ];

    protected static function booted(): void
    {
        self::deleted(function (GlobalOption $option) {
            $option->values()->delete();
        });
    }

    public function values(): HasMany
    {
        return $this
            ->hasMany(GlobalOptionValue::class, 'option_id')
            ->orderBy('order');
    }

    protected function optionName(): Attribute
    {
        return Attribute::get(fn () => $this->name);
    }
}
