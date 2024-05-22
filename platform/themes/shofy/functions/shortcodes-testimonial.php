<?php

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\FieldOptions\UiSelectorFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\UiSelectorField;
use Botble\Shortcode\Compilers\Shortcode as ShortcodeCompiler;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Shortcode\ShortcodeField;
use Botble\Testimonial\Models\Testimonial;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Arr;

app()->booted(function () {
    if (! is_plugin_active('testimonial')) {
        return;
    }

    Shortcode::register(
        'testimonials',
        __('Testimonials'),
        __('Testimonials'),
        function (ShortcodeCompiler $shortcode) {
            $testimonialIds = Shortcode::fields()->getIds('testimonial_ids', $shortcode);

            if (empty($testimonialIds)) {
                return null;
            }

            $testimonials = Testimonial::query()
                ->wherePublished()
                ->whereIn('id', $testimonialIds)
                ->get();

            if ($testimonials->isEmpty()) {
                return null;
            }

            return Theme::partial('shortcodes.testimonials.index', compact('shortcode', 'testimonials'));
        }
    );

    Shortcode::setAdminConfig('testimonials', function (array $attributes) {
        $testimonials = Testimonial::query()
            ->wherePublished()
            ->select(['id', 'name', 'company'])
            ->get()
            ->mapWithKeys(fn (Testimonial $item) => [$item->getKey() => sprintf('%s - %s', $item->name, $item->company)])
            ->all();

        $styles = [];

        foreach (range(1, 3) as $i) {
            $styles[$i] = [
                'label' => __('Style :number', ['number' => $i]),
                'image' => Theme::asset()->url("images/shortcodes/testimonials/style-$i.png"),
            ];
        }

        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'style',
                UiSelectorField::class,
                UiSelectorFieldOption::make()
                    ->choices($styles)
                    ->selected(Arr::get($attributes, 'style', 1))
                    ->toArray()
            )
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
                    ->toArray()
            )
            ->add(
                'subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Subtitle'))
                    ->toArray()
            )
            ->add(
                'testimonial_ids',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Testimonials'))
                    ->choices($testimonials)
                    ->multiple()
                    ->searchable()
                    ->selected(ShortcodeField::parseIds(Arr::get($attributes, 'testimonial_ids')))
                    ->toArray()
            );
    });
});
