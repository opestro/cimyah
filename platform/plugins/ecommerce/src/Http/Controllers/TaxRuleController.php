<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Forms\TaxRuleForm;
use Botble\Ecommerce\Http\Requests\TaxRuleRequest;
use Botble\Ecommerce\Models\Tax;
use Botble\Ecommerce\Models\TaxRule;
use Botble\Ecommerce\Tables\TaxRuleTable;
use Exception;
use Illuminate\Http\Request;

class TaxRuleController extends BaseController
{
    public function index(Tax $tax, TaxRuleTable $dataTable)
    {
        $this->pageTitle(trans('plugins/ecommerce::tax.rule.name', ['title' => $tax->title]));

        return $dataTable->renderTable();
    }

    public function create(Request $request)
    {
        $this->pageTitle(trans('plugins/ecommerce::tax.rule.create'));

        $form = TaxRuleForm::create()->renderForm();
        if ($request->ajax()) {
            return $this
                ->httpResponse()
                ->setData(['html' => $form])
                ->setMessage(PageTitle::getTitle(false));
        }

        return $form;
    }

    public function store(TaxRuleRequest $request)
    {
        $rule = TaxRule::query()->create($request->input());

        event(new CreatedContentEvent(TAX_RULE_MODULE_SCREEN_NAME, $request, $rule));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('tax.edit', $rule->tax_id))
            ->setNextUrl(route('tax.rule.edit', $rule->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(TaxRule $rule, TaxRuleRequest $request)
    {
        $this->pageTitle(trans('plugins/ecommerce::tax.rule.edit', ['title' => '#' . $rule->getKey()]));

        $form = TaxRuleForm::createFromModel($rule)->renderForm();

        if ($request->ajax()) {
            return $this
                ->httpResponse()
                ->setData(['html' => $form])
                ->setMessage(PageTitle::getTitle(false));
        }

        return $form;
    }

    public function update(TaxRule $rule, TaxRuleRequest $request)
    {
        $rule->fill($request->input());
        $rule->save();

        event(new UpdatedContentEvent(TAX_RULE_MODULE_SCREEN_NAME, $request, $rule));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('tax.edit', $rule->tax_id))
            ->setNextUrl(route('tax.rule.index', $rule->id))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(TaxRule $rule, Request $request)
    {
        try {
            $rule->delete();
            event(new DeletedContentEvent(TAX_RULE_MODULE_SCREEN_NAME, $request, $rule));

            return $this
                ->httpResponse()
                ->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
}
