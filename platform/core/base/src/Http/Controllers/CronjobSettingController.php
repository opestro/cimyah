<?php

namespace Botble\Base\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Setting\Facades\Setting;
use Carbon\Carbon;
use Illuminate\Console\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ProcessUtils;

class CronjobSettingController extends BaseSystemController
{
    public function index(): View
    {
        $this->pageTitle(trans('core/setting::setting.cronjob.name'));

        $command = sprintf(
            '* * * * * cd %s && %s >> /dev/null 2>&1',
            BaseHelper::hasDemoModeEnabled() ? 'path-to-your-project' : ProcessUtils::escapeArgument(base_path()),
            Application::formatCommandString('schedule:run')
        );

        $lastRunAt = Setting::get('cronjob_last_run_at');

        if ($lastRunAt) {
            $lastRunAt = Carbon::parse($lastRunAt);
        }

        return view('core/setting::cronjob', compact('command', 'lastRunAt'));
    }
}
