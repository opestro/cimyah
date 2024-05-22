<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Marketplace\Tables\ReviewTable;

class ReviewController extends BaseController
{
    public function index(ReviewTable $table)
    {
        $this->pageTitle(__('Reviews'));

        Assets::addStylesDirectly('vendor/core/plugins/ecommerce/css/review.css');

        return $table->renderTable();
    }
}
