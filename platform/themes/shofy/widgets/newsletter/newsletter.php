<?php

use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Newsletter\Forms\Fronts\NewsletterForm;
use Botble\Widget\AbstractWidget;
use Botble\Widget\Forms\WidgetForm;
use Illuminate\Support\Collection;

class NewsletterWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Newsletter form'),
            'description' => __('Display Newsletter form on sidebar'),
            'title' => __('Subscribe our Newsletter'),
            'subtitle' => __('Sale 20% off all store'),
            'shape_1' => null,
            'shape_2' => null,
            'shape_3' => null,
            'shape_4' => null,
        ]);
    }

    protected function data(): array|Collection
    {
        $form = NewsletterForm::create()
            ->remove(['wrapper_before', 'wrapper_after'])
            ->addBefore(
                'email',
                'open_wrapper',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content('<div class="tp-subscribe-input">')
                    ->toArray()
            )
            ->addAfter(
                'submit',
                'close_wrapper',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content('</div>')
                    ->toArray()
            )
            ->modify('submit', 'submit', [
                'attr' => [
                    'class' => '',
                ],
            ]);

        return compact('form');
    }

    protected function settingForm(): WidgetForm|string|null
    {
        $form = WidgetForm::createFromArray($this->getConfig())
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
                    ->toArray(),
            )
            ->add(
                'subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Subtitle'))
                    ->toArray(),
            );

        foreach (range(1, 4) as $i) {
            $form->add(
                sprintf('shape_%s', $i),
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Shape image :number', ['number' => $i]))
                    ->toArray(),
            );
        }

        return $form;
    }

    protected function requiredPlugins(): array
    {
        return ['newsletter'];
    }
}
