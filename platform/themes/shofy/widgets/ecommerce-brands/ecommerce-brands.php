<?php

use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Ecommerce\Models\Brand;
use Botble\Widget\AbstractWidget;
use Botble\Widget\Forms\WidgetForm;
use Illuminate\Support\Collection;

class EcommerceBrands extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Ecommerce Brands'),
            'description' => __('Display brands list'),
            'brands_id' => null,
            'style' => 'slider',
        ]);
    }

    protected function data(): array|Collection
    {
        $brandIds = $this->getConfig('brand_ids');

        if (empty($brandIds)) {
            return [
                'brands' => collect(),
            ];
        }

        $brands = Brand::query()
            ->wherePublished()
            ->whereIn('id', $brandIds)
            ->with('slugable')
            ->get();

        return compact('brands');
    }

    protected function settingForm(): WidgetForm|string|null
    {
        return WidgetForm::createFromArray($this->getConfig())
            ->add(
                'brand_ids',
                MultiCheckListField::class,
                MultiChecklistFieldOption::make()
                    ->label(__('Choose brands to display'))
                    ->choices(Brand::query()->pluck('name', 'id')->all())
                    ->multiple()
                    ->toArray()
            )
            ->add(
                'style',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(__('Display type'))
                    ->choices([
                        'slider' => __('Slider'),
                        'grid' => __('Grid'),
                    ])
                    ->toArray()
            );
    }

    protected function requiredPlugins(): array
    {
        return ['ecommerce'];
    }
}
