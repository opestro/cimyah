<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\DataSynchronize\Exporter\Exporter;
use Botble\DataSynchronize\Http\Controllers\ExportController;
use Botble\Ecommerce\Exporters\ProductPriceExporter;

class ExportProductPriceController extends ExportController
{
    protected function allowsSelectColumns(): bool
    {
        return false;
    }

    protected function getExporter(): Exporter
    {
        return ProductPriceExporter::make();
    }
}
