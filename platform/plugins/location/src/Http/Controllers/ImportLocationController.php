<?php

namespace Botble\Location\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\DataSynchronize\Http\Controllers\ImportController;
use Botble\DataSynchronize\Importer\Importer;
use Botble\Location\Facades\Location;
use Botble\Location\Http\Requests\ImportLocationRequest;
use Botble\Location\Importers\LocationImporter;

class ImportLocationController extends ImportController
{
    protected function getImporter(): Importer
    {
        return LocationImporter::make();
    }

    public function importLocationData(ImportLocationRequest $request)
    {
        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        $result = Location::downloadRemoteLocation(
            strtolower($request->input('country_code')),
            $request->boolean('continue')
        );

        return $this
            ->httpResponse()
            ->setError($result['error'])
            ->setMessage($result['message']);
    }
}
