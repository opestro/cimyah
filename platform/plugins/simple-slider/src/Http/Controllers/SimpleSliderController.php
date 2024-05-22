<?php

namespace Botble\SimpleSlider\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\SimpleSlider\Forms\SimpleSliderForm;
use Botble\SimpleSlider\Http\Requests\SimpleSliderRequest;
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\SimpleSlider\Models\SimpleSliderItem;
use Botble\SimpleSlider\Tables\SimpleSliderTable;
use Illuminate\Http\Request;

class SimpleSliderController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/simple-slider::simple-slider.menu'), route('simple-slider.index'));
    }

    public function index(SimpleSliderTable $dataTable)
    {
        $this->pageTitle(trans('plugins/simple-slider::simple-slider.menu'));

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/simple-slider::simple-slider.create'));

        return SimpleSliderForm::create()
            ->removeMetaBox('slider-items')
            ->renderForm();
    }

    public function store(SimpleSliderRequest $request)
    {
        $form = SimpleSliderForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('simple-slider.index')
            ->setNextRoute('simple-slider.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(SimpleSlider $simpleSlider)
    {
        Assets::addScripts('sortable')
            ->addScriptsDirectly('vendor/core/plugins/simple-slider/js/simple-slider-admin.js');

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $simpleSlider->name]));

        return SimpleSliderForm::createFromModel($simpleSlider)
            ->renderForm();
    }

    public function update(SimpleSlider $simpleSlider, SimpleSliderRequest $request)
    {
        SimpleSliderForm::createFromModel($simpleSlider)->setRequest($request)->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('simple-slider.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(SimpleSlider $simpleSlider)
    {
        return DeleteResourceAction::make($simpleSlider);
    }

    public function postSorting(Request $request)
    {
        foreach ($request->input('items', []) as $key => $id) {
            SimpleSliderItem::query()->where('id', $id)->update(['order' => ($key + 1)]);
        }

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/simple-slider::simple-slider.update_slide_position_success'));
    }
}
