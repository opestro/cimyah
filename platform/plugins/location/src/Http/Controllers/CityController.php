<?php

namespace Botble\Location\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Location\Forms\CityForm;
use Botble\Location\Http\Requests\CityRequest;
use Botble\Location\Http\Resources\CityResource;
use Botble\Location\Models\City;
use Botble\Location\Tables\CityTable;
use Illuminate\Http\Request;

class CityController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/location::location.name'))
            ->add(trans('plugins/location::city.name'), route('city.index'));
    }

    public function index(CityTable $table)
    {
        $this->pageTitle(trans('plugins/location::city.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/location::city.create'));

        return CityForm::create()->renderForm();
    }

    public function store(CityRequest $request)
    {
        $form = CityForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('city.index')
            ->setNextRoute('city.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(City $city)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $city->name]));

        return CityForm::createFromModel($city)->renderForm();
    }

    public function update(City $city, CityRequest $request)
    {
        CityForm::createFromModel($city)->setRequest($request)->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('city.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(City $city)
    {
        return DeleteResourceAction::make($city);
    }

    public function getList(Request $request)
    {
        $keyword = BaseHelper::stringify($request->input('q'));

        if (! $keyword) {
            return $this
                ->httpResponse()
                ->setData([]);
        }

        $data = City::query()
            ->where('name', 'LIKE', '%' . $keyword . '%')
            ->select(['id', 'name'])
            ->take(10)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $data->prepend(new City(['id' => 0, 'name' => trans('plugins/location::city.select_city')]));

        return $this
            ->httpResponse()
            ->setData(CityResource::collection($data));
    }

    public function ajaxGetCities(Request $request)
    {
        $data = City::query()
            ->select(['id', 'name'])
            ->wherePublished()
            ->orderBy('order')
            ->orderBy('name');

        $stateId = $request->input('state_id');

        if ($stateId && $stateId != 'null') {
            $data = $data->where('state_id', $stateId);
        }

        $countryId = $request->input('country_id');

        if ($countryId && $countryId != 'null') {
            $data = $data->where('country_id', $countryId);
        }

        $keyword = BaseHelper::stringify($request->query('k'));

        if ($keyword) {
            $data = $data
                ->where('name', 'LIKE', '%' . $keyword . '%')
                ->paginate(10);
        } else {
            $data = $data->get();
        }

        if ($keyword) {
            return $this
                ->httpResponse()
                ->setData([CityResource::collection($data), 'total' => $data->total()]);
        }

        $data->prepend(new City(['id' => 0, 'name' => trans('plugins/location::city.select_city')]));

        return $this
            ->httpResponse()
            ->setData(CityResource::collection($data));
    }
}
