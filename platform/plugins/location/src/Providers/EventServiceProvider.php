<?php

namespace Botble\Location\Providers;

use Botble\Location\Events\ImportedCityEvent;
use Botble\Location\Events\ImportedCountryEvent;
use Botble\Location\Events\ImportedStateEvent;
use Botble\Location\Listeners\CreateCityTranslationListener;
use Botble\Location\Listeners\CreateCountryTranslationListener;
use Botble\Location\Listeners\CreateStateTranslationListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseServiceProvider;

class EventServiceProvider extends BaseServiceProvider
{
    protected $listen = [
        ImportedCountryEvent::class => [
            CreateCountryTranslationListener::class,
        ],
        ImportedStateEvent::class => [
            CreateStateTranslationListener::class,
        ],
        ImportedCityEvent::class => [
            CreateCityTranslationListener::class,
        ],
    ];
}
