<?php

namespace Botble\Theme\Providers;

use Botble\Theme\Facades\Theme;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Move base routes to a service provider to make sure all filters & actions can hook to base routes
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            if (Theme::hasInheritTheme()) {
                $this->loadRoutesFromTheme(Theme::getInheritTheme());
            }

            $this->loadRoutesFromTheme(Theme::getThemeName());
        });
    }

    protected function loadRoutesFromTheme(string $theme): void
    {
        $routeFilePath = theme_path($theme . '/routes/web.php');

        if ($this->app['files']->exists($routeFilePath)) {
            $this->loadRoutesFrom($routeFilePath);
        }
    }
}
