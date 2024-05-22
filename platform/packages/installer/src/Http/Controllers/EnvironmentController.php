<?php

namespace Botble\Installer\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Installer\Events\EnvironmentSaved;
use Botble\Installer\Http\Requests\SaveEnvironmentRequest;
use Botble\Installer\Services\ImportDatabaseService;
use Botble\Installer\Supports\EnvironmentManager;
use Botble\Theme\Facades\Manager;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class EnvironmentController extends BaseController
{
    public function index(Request $request): View|RedirectResponse
    {
        if (! URL::hasValidSignature($request)) {
            return redirect()->route('installers.requirements.index');
        }

        return view('packages/installer::environment');
    }

    public function store(
        SaveEnvironmentRequest $request,
        EnvironmentManager $environmentManager,
        ImportDatabaseService $importDatabaseService
    ): RedirectResponse {
        $driverName = $request->input('database_connection');
        $connectionName = 'database.connections.' . $driverName;
        $databaseName = $request->input('database_name');

        config([
            'database.default' => $driverName,
            $connectionName => array_merge(config($connectionName), [
                'host' => $request->input('database_hostname'),
                'port' => $request->input('database_port'),
                'database' => $databaseName,
                'username' => $request->input('database_username'),
                'password' => $request->input('database_password'),
            ]),
        ]);

        if (count(Manager::getThemes()) > 1) {
            $nextRouteName = 'installers.themes.index';
        } else {
            $nextRouteName = 'installers.accounts.index';
            $importDatabaseService->handle(base_path('database.sql'));
        }

        $results = $environmentManager->save($request);

        event(new EnvironmentSaved($request));

        BaseHelper::saveFileData(storage_path(INSTALLING_SESSION_NAME), Carbon::now()->toDateTimeString());

        return redirect()
            ->to(URL::temporarySignedRoute($nextRouteName, Carbon::now()->addMinutes(30)))
            ->with('install_message', $results);
    }
}
