<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\DataSynchronize\Exporter\Exporter;
use Botble\DataSynchronize\Http\Controllers\ExportController;
use Botble\Ecommerce\Exporters\ProductInventoryExporter;

class ExportProductInventoryController extends ExportController
{
    protected function allowsSelectColumns(): bool
    {
        return false;
    }

    protected function getExporter(): Exporter
    {
        return ProductInventoryExporter::make();
    }
}
