<?php

namespace Botble\RequestLog\Providers;

use Botble\Base\Facades\Assets;
use Botble\Base\Supports\ServiceProvider;
use Botble\Dashboard\Events\RenderingDashboardWidgets;
use Botble\Dashboard\Supports\DashboardWidgetInstance;
use Botble\RequestLog\Events\RequestHandlerEvent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_action(BASE_ACTION_SITE_ERROR, [$this, 'handleSiteError'], 125);

        $this->app['events']->listen(RenderingDashboardWidgets::class, function () {
            add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'registerDashboardWidgets'], 125, 2);
        });
    }

    public function handleSiteError(int $code): void
    {
        event(new RequestHandlerEvent($code));
    }

    public function registerDashboardWidgets(array $widgets, Collection $widgetSettings): array
    {
        if (! Auth::guard()->user()->hasPermission('request-log.index')) {
            return $widgets;
        }

        Assets::addScriptsDirectly(['vendor/core/plugins/request-log/js/request-log.js']);

        return (new DashboardWidgetInstance())
            ->setPermission('request-log.index')
            ->setKey('widget_request_errors')
            ->setTitle(trans('plugins/request-log::request-log.widget_request_errors'))
            ->setRoute(route('request-log.widget.request-errors'))
            ->setColumn('col-md-6 col-sm-6')
            ->init($widgets, $widgetSettings);
    }
}
