<?php

use Botble\Base\Forms\FieldOptions\ColorFieldOption;
use Botble\Base\Forms\FieldOptions\CoreIconFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\FieldOptions\UiSelectorFieldOption;
use Botble\Base\Forms\Fields\ColorField;
use Botble\Base\Forms\Fields\CoreIconField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\UiSelectorField;
use Botble\Ecommerce\Models\Brand;
use Botble\Shortcode\Compilers\Shortcode as ShortcodeCompiler;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\FieldOptions\ShortcodeTabsFieldOption;
use Botble\Shortcode\Forms\Fields\ShortcodeTabsField;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Shortcode\ShortcodeField;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Supports\ThemeSupport;
use Illuminate\Support\Arr;

app()->booted(function () {
    ThemeSupport::registerGoogleMapsShortcode(Theme::getThemeNamespace('partials.shortcodes.google-maps'));
    ThemeSupport::registerYoutubeShortcode();

    Shortcode::register('site-features', __('Site Features'), __('Site Features'), function (ShortcodeCompiler $shortcode) {
        $tabs = Shortcode::fields()->getTabsData(['title', 'description', 'icon'], $shortcode);

        return Theme::partial('shortcodes.site-features.index', compact('shortcode', 'tabs'));
    });

    Shortcode::setPreviewImage('site-features', Theme::asset()->url('images/shortcodes/site-features/style-1.png'));

    Shortcode::setAdminConfig('site-features', function (array $attributes) {
        $styles = [];

        foreach (range(1, 4) as $i) {
            $styles[$i] = [
                'label' => __('Style :number', ['number' => $i]),
                'image' => Theme::asset()->url(sprintf('images/shortcodes/site-features/style-%s.png', $i)),
            ];
        }

        return ShortcodeForm::createFromArray($attributes)
            ->add(
                'style',
                UiSelectorField::class,
                UiSelectorFieldOption::make()
                    ->choices($styles)
                    ->selected(Arr::get($attributes, 'style', 1))
                    ->toArray()
            )
            ->add(
                'features',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->fields([
                        'title' => [
                            'type' => 'text',
                            'title' => __('Title'),
                            'required' => true,
                        ],
                        'description' => [
                            'type' => 'textarea',
                            'title' => __('Description'),
                            'required' => false,
                        ],
                        'icon' => [
                            'type' => 'coreIcon',
                            'title' => __('Icon'),
                            'required' => true,
                        ],
                    ])
                    ->attrs($attributes)
                    ->toArray()
            )
            ->add(
                'icon_color',
                ColorField::class,
                ColorFieldOption::make()
                    ->label(__('Icon color'))
                    ->defaultValue('#fd4b6b')
                    ->toArray()
            );
    });

    Shortcode::register('app-downloads', __('App Downloads'), __('App Downloads'), function (ShortcodeCompiler $shortcode): ?string {
        $platforms = Shortcode::fields()->getTabsData(['image', 'url'], $shortcode);

        return Theme::partial('shortcodes.app-downloads.index', compact('shortcode', 'platforms'));
    });

    Shortcode::setPreviewImage('app-downloads', Theme::asset()->url('images/shortcodes/app-downloads.png'));

    Shortcode::setAdminConfig('app-downloads', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
                    ->toArray()
            )
            ->add(
                'open_wrapper_google',
                HtmlField::class,
                ['html' => '<div class="form-fieldset">']
            )
            ->add(
                'google_label',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Google label'))
                    ->placeholder(__('Enter Google label'))
                    ->toArray()
            )
            ->add(
                'google_icon',
                CoreIconField::class,
                CoreIconFieldOption::make()
                    ->label(__('Google Play icon'))
                    ->toArray()
            )
            ->add(
                'google_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Google URL'))
                    ->placeholder(__('Enter Google URL'))
                    ->toArray()
            )
            ->add('close_wrapper_google', HtmlField::class, ['html' => '</div>'])
            ->add('open_wrapper_apple', HtmlField::class, ['html' => '<div class="form-fieldset">'])
            ->add(
                'apple_label',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Apple label'))
                    ->placeholder(__('Enter Apple label'))
                    ->toArray()
            )
            ->add(
                'apple_icon',
                CoreIconField::class,
                CoreIconFieldOption::make()
                    ->label(__('Apple icon'))
                    ->toArray()
            )
            ->add(
                'apple_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Apple URL'))
                    ->placeholder(__('Enter Apple URL'))
                    ->toArray()
            )
            ->add('close_wrapper_apple', HtmlField::class, ['html' => '</div>'])
            ->add(
                'screenshot',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Mobile screenshot'))
                    ->toArray()
            )
            ->add(
                'shape_image_left',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Shape image left'))
                    ->toArray()
            )
            ->add(
                'shape_image_right',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Shape image right'))
                    ->toArray()
            );
    });

    Shortcode::register(
        'image-slider',
        __('Image Slider'),
        __('Dynamic carousel for featured content with customizable links.'),
        function (ShortcodeCompiler $shortcode) {
            $tabs = [];
            $brands = [];

            switch ($shortcode->type) {
                case 'custom':
                    $tabs = Shortcode::fields()->getTabsData(['name', 'image', 'url'], $shortcode);

                    if (empty($tabs)) {
                        return null;
                    }

                    break;

                case 'brands':
                    $brandIds = Shortcode::fields()->getIds('brand_ids', $shortcode);

                    if (empty($brandIds)) {
                        return null;
                    }

                    $brands = Brand::query()
                        ->wherePublished()
                        ->whereIn('id', $brandIds)
                        ->get();

                    if (empty($brands)) {
                        return null;
                    }

                    break;
            }

            return Theme::partial('shortcodes.image-slider.index', compact('shortcode', 'tabs', 'brands'));
        }
    );

    Shortcode::setPreviewImage('image-slider', Theme::asset()->url('images/shortcodes/image-slider.png'));

    Shortcode::setAdminConfig('image-slider', function (array $attributes) {
        $types = [
            'custom' => __('Custom'),
        ];

        if (is_plugin_active('ecommerce')) {
            $types['brands'] = __('Brands');
        }

        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'type',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(__('Get data from to show'))
                    ->choices($types)
                    ->attributes([
                        'data-bb-toggle' => 'collapse',
                        'data-bb-target' => '.image-slider',
                    ])
                    ->toArray(),
            )
            ->when(is_plugin_active('ecommerce'), function (ShortcodeForm $form) use ($attributes) {
                $form->add(
                    'brand_ids',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Brands'))
                        ->choices(
                            Brand::query()
                                ->wherePublished()
                                ->pluck('name', 'id')
                                ->all()
                        )
                        ->selected(ShortcodeField::parseIds(Arr::get($attributes, 'brand_ids')))
                        ->searchable()
                        ->multiple()
                        ->wrapperAttributes([
                            'class' => 'mb-3 position-relative image-slider',
                            'data-bb-value' => 'brands',
                            'style' => sprintf('display: %s', Arr::get($attributes, 'type') === 'brands' ? 'block' : 'none'),
                        ])
                        ->toArray(),
                );
            })
            ->add(
                'open_tabs_wrapper',
                HtmlField::class,
                ['html' => sprintf('<div class="image-slider" data-bb-value="custom" style="display: %s">', Arr::get($attributes, 'type') === 'custom' ? 'block' : 'none')]
            )
            ->add(
                'tabs',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->fields([
                        'name' => [
                            'type' => 'text',
                            'title' => __('Name'),
                        ],
                        'image' => [
                            'type' => 'image',
                            'title' => __('Image'),
                            'required' => true,
                        ],
                        'url' => [
                            'type' => 'text',
                            'title' => __('URL'),
                        ],
                    ])
                    ->attrs($attributes)
                    ->toArray()
            )
            ->add('close_tabs_wrapper', HtmlField::class, ['html' => '</div>']);
    });

    Shortcode::register('about', __('About'), __('About'), function (ShortcodeCompiler $shortcode) {
        return Theme::partial('shortcodes.about.index', compact('shortcode'));
    });

    Shortcode::setPreviewImage('about', Theme::asset()->url('images/shortcodes/about.png'));

    Shortcode::setAdminConfig('about', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->columns()
            ->add(
                'image_1',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image 1'))
                    ->toArray()
            )
            ->add(
                'image_2',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image 2'))
                    ->toArray()
            )
            ->add(
                'subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Subtitle'))
                    ->colspan(2)
                    ->toArray()
            )
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
                    ->colspan(2)
                    ->toArray()
            )
            ->add(
                'description',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(__('Description'))
                    ->colspan(2)
                    ->toArray()
            )
            ->add(
                'action_label',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Action label'))
                    ->toArray(),
            )
            ->add(
                'action_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Action URL'))
                    ->toArray(),
            );
    });
});
