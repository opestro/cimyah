<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\DataSynchronize\Exporter\Exporter;
use Botble\DataSynchronize\Http\Controllers\ExportController;
use Botble\Ecommerce\Exporters\ProductExporter;

class ExportProductController extends ExportController
{
    protected function getExporter(): Exporter
    {
        return ProductExporter::make();
    }
}
