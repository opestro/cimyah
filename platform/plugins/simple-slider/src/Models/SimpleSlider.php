<?php

namespace Botble\SimpleSlider\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SimpleSlider extends BaseModel
{
    protected $table = 'simple_sliders';

    protected $fillable = [
        'name',
        'key',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'key' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    protected static function booted(): void
    {
        static::deleted(function (SimpleSlider $slider) {
            $slider->sliderItems()->each(fn (SimpleSliderItem $item) => $item->delete());
        });
    }

    public function sliderItems(): HasMany
    {
        return $this->hasMany(SimpleSliderItem::class)->orderBy('simple_slider_items.order');
    }
}
