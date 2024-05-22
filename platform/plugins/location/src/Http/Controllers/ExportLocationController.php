<?php

namespace Botble\Location\Http\Controllers;

use Botble\DataSynchronize\Exporter\Exporter;
use Botble\DataSynchronize\Http\Controllers\ExportController;
use Botble\Location\Exporters\LocationExporter;

class ExportLocationController extends ExportController
{
    protected function getExporter(): Exporter
    {
        return LocationExporter::make();
    }
}
