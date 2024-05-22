<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\DataSynchronize\Http\Controllers\ImportController;
use Botble\DataSynchronize\Importer\Importer;
use Botble\Ecommerce\Importers\ProductPriceImporter;

class ImportProductPriceController extends ImportController
{
    protected function getImporter(): Importer
    {
        return ProductPriceImporter::make();
    }
}
