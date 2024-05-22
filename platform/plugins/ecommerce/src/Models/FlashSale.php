<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FlashSale extends BaseModel
{
    protected $table = 'ec_flash_sales';

    protected $fillable = [
        'name',
        'end_date',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'end_date' => 'date',
        'name' => SafeContent::class,
    ];

    protected static function booted(): void
    {
        static::deleted(fn (FlashSale $flashSale) => $flashSale->products()->detach());
    }

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class, 'ec_flash_sale_products', 'flash_sale_id', 'product_id')
            ->withPivot(['price', 'quantity', 'sold']);
    }

    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->whereDate('end_date', '>=', Carbon::now());
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->whereDate('end_date', '<', Carbon::now());
    }

    protected function expired(): Attribute
    {
        return Attribute::get(fn (): bool => $this->end_date->lessThan(Carbon::now()->startOfDay()));
    }

    protected function saleCountLeftLabel(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (! $this->pivot) {
                return null;
            }

            return $this->pivot->sold . '/' . $this->pivot->quantity;
        })->shouldCache();
    }

    protected function saleCountLeftPercent(): Attribute
    {
        return Attribute::get(function (): float {
            if (! $this->pivot) {
                return 0;
            }

            return $this->pivot->quantity > 0 ? ($this->pivot->sold / $this->pivot->quantity) * 100 : 0;
        })->shouldCache();
    }
}
