<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Forms\TaxForm;
use Botble\Ecommerce\Http\Controllers\Settings\SettingController;
use Botble\Ecommerce\Http\Requests\TaxRequest;
use Botble\Ecommerce\Models\Tax;
use Botble\Ecommerce\Tables\TaxTable;

class TaxController extends SettingController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::tax.name'), route('ecommerce.settings.taxes'));
    }

    public function index(TaxTable $dataTable)
    {
        $this->pageTitle(trans('plugins/ecommerce::tax.name'));

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/ecommerce::tax.create'));

        return TaxForm::create()->renderForm();
    }

    public function store(TaxRequest $request)
    {
        $tax = Tax::query()->create($request->input());

        event(new CreatedContentEvent(TAX_MODULE_SCREEN_NAME, $request, $tax));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('ecommerce.settings.taxes'))
            ->setNextUrl(route('tax.edit', $tax->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(Tax $tax)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $tax->title]));

        return TaxForm::createFromModel($tax)->renderForm();
    }

    public function update(Tax $tax, TaxRequest $request)
    {
        $tax->fill($request->input());
        $tax->save();

        event(new UpdatedContentEvent(TAX_MODULE_SCREEN_NAME, $request, $tax));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('tax.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Tax $tax)
    {
        return DeleteResourceAction::make($tax);
    }
}
