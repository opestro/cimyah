<?php

namespace Botble\Location\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Location\Forms\StateForm;
use Botble\Location\Http\Requests\StateRequest;
use Botble\Location\Http\Resources\StateResource;
use Botble\Location\Models\State;
use Botble\Location\Tables\StateTable;
use Illuminate\Http\Request;

class StateController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/location::location.name'))
            ->add(trans('plugins/location::state.name'), route('state.index'));
    }

    public function index(StateTable $table)
    {
        $this->pageTitle(trans('plugins/location::state.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/location::state.create'));

        return StateForm::create()->renderForm();
    }

    public function store(StateRequest $request)
    {
        $form = StateForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('state.index')
            ->setNextRoute('state.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(State $state)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $state->name]));

        return StateForm::createFromModel($state)->renderForm();
    }

    public function update(State $state, StateRequest $request)
    {
        StateForm::createFromModel($state)->setRequest($request)->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('state.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(State $state)
    {
        return DeleteResourceAction::make($state);
    }

    public function getList(Request $request)
    {
        $keyword = BaseHelper::stringify($request->input('q'));

        if (! $keyword) {
            return $this
                ->httpResponse()
                ->setData([]);
        }

        $data = State::query()
            ->where('name', 'LIKE', '%' . $keyword . '%')
            ->select(['id', 'name'])
            ->take(10)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $data->prepend(new State(['id' => 0, 'name' => trans('plugins/location::city.select_state')]));

        return $this
            ->httpResponse()
            ->setData(StateResource::collection($data));
    }

    public function ajaxGetStates(Request $request)
    {
        $data = State::query()
            ->select(['id', 'name'])
            ->wherePublished()
            ->orderBy('order')
            ->orderBy('name');

        $countryId = $request->input('country_id');

        if ($countryId && $countryId != 'null') {
            $data = $data->where('country_id', $countryId);
        }

        $data = $data->get();

        $data->prepend(new State(['id' => 0, 'name' => trans('plugins/location::city.select_state')]));

        return $this
            ->httpResponse()
            ->setData(StateResource::collection($data));
    }
}
