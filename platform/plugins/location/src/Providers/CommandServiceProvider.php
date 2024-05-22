<?php

namespace Botble\Location\Providers;

use Botble\Base\Supports\ServiceProvider;
use Botble\Location\Commands\MigrateLocationCommand;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            MigrateLocationCommand::class,
        ]);
    }
}
