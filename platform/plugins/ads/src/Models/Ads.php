<?php

namespace Botble\Ads\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Ads extends BaseModel
{
    protected $table = 'ads';

    protected $fillable = [
        'name',
        'key',
        'status',
        'open_in_new_tab',
        'expired_at',
        'location',
        'image',
        'tablet_image',
        'mobile_image',
        'url',
        'clicked',
        'order',
        'ads_type',
        'google_adsense_slot_id',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'expired_at' => 'date',
        'open_in_new_tab' => 'boolean',
    ];

    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->whereDate('expired_at', '>=', Carbon::now());
    }

    protected function randomHash(): Attribute
    {
        return Attribute::get(fn () => hash('sha1', $this->key . $this->getKey()));
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(
            function (): string {
                return $this->parseImageUrl();
            }
        );
    }

    protected function tabletImageUrl(): Attribute
    {
        return Attribute::get(
            function (): string {
                return $this->parseImageUrl('tablet');
            }
        );
    }

    protected function mobileImageUrl(): Attribute
    {
        return Attribute::get(
            function (): string {
                return $this->parseImageUrl('mobile');
            }
        );
    }

    protected function clickUrl(): Attribute
    {
        return Attribute::get(
            fn ($_, array $attributes = []): string =>
                route('public.ads-click.alternative', [
                    'randomHash' => $this->random_hash,
                    'adsKey' => $attributes['key'],
                ])
        );
    }

    public function parseImageUrl(string $size = 'default'): string
    {
        return route('public.ads-click.image', [
            'randomHash' => $this->random_hash,
            'adsKey' => $this->key,
            'size' => $size,
            'hashName' => md5($this->key),
        ]);
    }
}
