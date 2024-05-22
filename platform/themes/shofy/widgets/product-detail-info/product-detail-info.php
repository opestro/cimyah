<?php

use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\RepeaterFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\RepeaterField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Widget\AbstractWidget;
use Botble\Widget\Forms\WidgetForm;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ProductDetailInfoWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Product detail info'),
            'description' => __('Display extra information for product detail page.'),
        ]);
    }

    protected function data(): array|Collection
    {
        $data = $this->getConfig('messages');
        $messages = [];

        if ($data) {
            $messages = collect($data)->transform(fn ($item) => Arr::get($item, '0.value'));
        }

        return [
            'messages' => $messages,
        ];
    }

    protected function settingForm(): WidgetForm|string|null
    {
        return WidgetForm::createFromArray($this->getConfig())
            ->add(
                'messages',
                RepeaterField::class,
                RepeaterFieldOption::make()
                    ->label(__('Instructions'))
                    ->fields([
                        [
                            'label' => __('Message'),
                            'type' => 'text',
                            'attributes' => [
                                'name' => 'message',
                                'value' => null,
                                'options' => ['class' => 'form-control'],
                            ],
                        ],
                    ])
                    ->toArray()
            )
            ->add(
                'description',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(__('Description'))
                    ->toArray(),
            )
            ->add(
                'image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image'))
                    ->toArray(),
            );
    }

    protected function requiredPlugins(): array
    {
        return ['ecommerce'];
    }
}
