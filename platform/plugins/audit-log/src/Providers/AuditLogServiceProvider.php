<?php

namespace Botble\AuditLog\Providers;

use Botble\AuditLog\Facades\AuditLog;
use Botble\AuditLog\Models\AuditHistory;
use Botble\AuditLog\Repositories\Eloquent\AuditLogRepository;
use Botble\AuditLog\Repositories\Interfaces\AuditLogInterface;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\PanelSections\System\SystemPanelSection;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Console\PruneCommand;
use Illuminate\Foundation\AliasLoader;

/**
 * @since 02/07/2016 09:05 AM
 */
class AuditLogServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(AuditLogInterface::class, function () {
            return new AuditLogRepository(new AuditHistory());
        });
    }

    public function boot(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);

        $this
            ->setNamespace('plugins/audit-log')
            ->loadHelpers()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->publishAssets();

        AliasLoader::getInstance()->alias('AuditLog', AuditLog::class);

        PanelSectionManager::group('system')->beforeRendering(function () {
            PanelSectionManager::registerItem(
                SystemPanelSection::class,
                fn () => PanelSectionItem::make('audit-logs')
                    ->setTitle(trans('plugins/audit-log::history.name'))
                    ->withDescription(trans('plugins/audit-log::history.description'))
                    ->withIcon('ti ti-note')
                    ->withPriority(10)
                    ->withRoute('audit-log.index')
            );
        });

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });

        if ($this->app->runningInConsole()) {
            $this->app->afterResolving(Schedule::class, function (Schedule $schedule) {
                $schedule
                    ->command(PruneCommand::class, ['--model' => AuditHistory::class])
                    ->dailyAt('00:30');
            });
        }
    }
}
