<?php

namespace Botble\Ads\Http\Controllers;

use Botble\Ads\Models\Ads;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Media\Facades\RvMedia;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PublicController extends BaseController
{
    public function getAdsClick(string $key, BaseHttpResponse $response)
    {
        $ads = Ads::query()->where('key', $key)->first();

        if (! $ads || ! $ads->url) {
            return $response->setNextUrl(route('public.single'));
        }

        $ads::withoutEvents(fn () => $ads::withoutTimestamps(fn () => $ads->increment('clicked')));

        return $response->setNextUrl($ads->url);
    }

    public function getAdsImage(string $randomHash, string $adsKey, string $size, string $hashName, BaseHttpResponse $response)
    {
        $ads = Ads::query()->where('key', $adsKey)->firstOrFail();

        if (! $ads) {
            abort(404);
        }

        abort_if($randomHash !== $ads->random_hash, 404);

        abort_if(! Str::of(
            $ads->parseImageUrl($size)
        )->endsWith($hashName), 404);

        if ($size === 'tablet') {
            $image = $ads->tablet_image ?: $ads->image;
        } elseif ($size === 'mobile') {
            $image = ($ads->mobile_image ?: $ads->mobile_image) ?: $ads->image;
        } else {
            $image = $ads->image;
        }

        if (! $image) {
            abort(404);
        }

        $realPath = RvMedia::getRealPath($image);

        if (Str::startsWith($realPath, ['http://', 'https://'])) {
            return $response->setNextUrl($realPath);
        }

        if (! File::exists($realPath)) {
            abort(404);
        }

        return response()->file($realPath);
    }

    public function getAdsClickAlternative(string $randomHash, string $adsKey)
    {
        return app()->call([$this, 'getAdsClick'], ['key' => $adsKey]);
    }
}
