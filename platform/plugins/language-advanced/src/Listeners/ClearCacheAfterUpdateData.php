<?php

namespace Botble\LanguageAdvanced\Listeners;

use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Models\BaseModel;
use Botble\Support\Services\Cache\Cache;

class ClearCacheAfterUpdateData
{
    public function handle(UpdatedContentEvent $event): void
    {
        if (! setting('enable_cache', false) || ! $event->data instanceof BaseModel) {
            return;
        }

        $cache = new Cache(app('cache'), $event->data::class);
        $cache->flush();
    }
}
