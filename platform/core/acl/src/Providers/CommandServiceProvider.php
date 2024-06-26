<?php

namespace Botble\ACL\Providers;

use Botble\ACL\Commands\UserCreateCommand;
use Botble\ACL\Commands\UserPasswordCommand;
use Botble\Base\Supports\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            UserCreateCommand::class,
            UserPasswordCommand::class,
        ]);
    }
}
