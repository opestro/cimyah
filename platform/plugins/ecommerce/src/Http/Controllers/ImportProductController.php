<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\DataSynchronize\Http\Controllers\ImportController;
use Botble\DataSynchronize\Importer\Importer;
use Botble\Ecommerce\Importers\ProductImporter;
use Illuminate\Http\Request;

class ImportProductController extends ImportController
{
    protected function getImporter(): Importer
    {
        return ProductImporter::make();
    }

    protected function prepareImporter(Request $request): Importer
    {
        /** @var ProductImporter $importer */
        return parent::prepareImporter($request)
            ->setImportType($request->input('type'));
    }
}
