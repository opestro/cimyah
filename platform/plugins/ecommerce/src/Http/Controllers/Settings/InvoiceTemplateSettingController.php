<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\BaseHelper;
use Botble\Ecommerce\Http\Requests\Settings\InvoiceTemplateSettingRequest;
use Botble\Ecommerce\Supports\InvoiceHelper;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;

class InvoiceTemplateSettingController extends SettingController
{
    public function edit(InvoiceHelper $invoiceHelper): View
    {
        $this->pageTitle(trans('plugins/ecommerce::setting.invoice_templates'));

        Assets::addScriptsDirectly('vendor/core/core/setting/js/email-template.js');

        $content = $invoiceHelper->getInvoiceTemplate();
        $variables = $invoiceHelper->getVariables();

        return view('plugins/ecommerce::invoice-template.settings', compact('content', 'variables'));
    }

    public function update(InvoiceTemplateSettingRequest $request)
    {
        BaseHelper::saveFileData(storage_path('app/templates/invoice.tpl'), $request->input('content'), false);

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }

    public function reset()
    {
        File::delete(storage_path('app/templates/invoice.tpl'));

        return $this
            ->httpResponse()
            ->setMessage(trans('core/setting::setting.email.reset_success'));
    }

    public function preview(InvoiceHelper $invoiceHelper)
    {
        $invoice = $invoiceHelper->getDataForPreview();

        return $invoiceHelper->streamInvoice($invoice);
    }
}
