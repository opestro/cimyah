<?php

use Botble\Ads\Facades\AdsManager;
use Botble\Ads\Models\Ads;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\UiSelectorFieldOption;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\UiSelectorField;
use Botble\Shortcode\Compilers\Shortcode as ShortcodeCompiler;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Arr;

app('events')->listen(RouteMatched::class, function () {
    if (! is_plugin_active('ads')) {
        return;
    }

    Shortcode::register('ads', __('Ads'), __('Ads'), function (ShortcodeCompiler $shortcode) {
        if (! $shortcode->key_1 && ! $shortcode->key_2 && ! $shortcode->key_3 && ! $shortcode->key_4) {
            return null;
        }

        $data = Ads::query()
            ->whereIn('key', [$shortcode->key_1, $shortcode->key_2, $shortcode->key_3, $shortcode->key_4])
            ->wherePublished()
            ->orderBy('order')
            ->get();

        $ads = [];

        foreach (range(1, 4) as $i) {
            if ($shortcode->{'key_' . $i}) {
                $ads[] = $data->where('key', $shortcode->{'key_' . $i})->first();
            }
        }

        $ads = array_filter($ads);

        if (empty($ads)) {
            return null;
        }

        return Theme::partial('shortcodes.ads.index', compact('shortcode', 'ads'));
    });

    Shortcode::setPreviewImage('ads', Theme::asset()->url('images/shortcodes/ads/style-1.png'));

    Shortcode::setAdminConfig('ads', function (array $attributes) {
        $ads = AdsManager::getData(true, true)
            ->pluck('name', 'key')
            ->merge(['' => __('-- Select --')])
            ->sortKeys()
            ->all();

        $styles = [];

        foreach (range(1, 4) as $i) {
            $styles[$i] = [
                'label' => __('Style :number', ['number' => $i]),
                'image' => Theme::asset()->url("images/shortcodes/ads/style-$i.png"),
            ];
        }

        $form = ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'style',
                UiSelectorField::class,
                UiSelectorFieldOption::make()
                    ->label(__('Style'))
                    ->choices($styles)
                    ->collapsible('style')
                    ->toArray()
            );

        foreach (range(1, 4) as $i) {
            $form->add(
                "key_$i",
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Ad :number', ['number' => $i]))
                    ->choices($ads)
                    ->toArray()
            );
        }

        $form->add(
            'full_width',
            OnOffCheckboxField::class,
            CheckboxFieldOption::make()
                ->label(__('Full width'))
                ->collapseTrigger('style', 2, Arr::get($attributes, 'style', 1) == 2)
                ->toArray()
        );

        return $form;
    });
});
