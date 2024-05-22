<?php

use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\FieldOptions\UiSelectorFieldOption;
use Botble\Base\Forms\Fields\CheckboxField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\UiSelectorField;
use Botble\Faq\Models\Faq;
use Botble\Faq\Models\FaqCategory;
use Botble\Shortcode\Compilers\Shortcode as ShortcodeCompiler;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Arr;

if (is_plugin_active('faq')) {
    Shortcode::register('faqs', __('FAQs'), __('FAQs'), function (ShortcodeCompiler $shortcode): ?string {
        $categoryIds = Shortcode::fields()->parseIds($shortcode->category_ids);

        if (empty($categoryIds)) {
            return null;
        }

        $style = in_array($shortcode->style, ['list', 'group']) ? $shortcode->style : 'list';

        $faqs = collect();
        $categories = collect();

        if ($style === 'list') {
            $faqs = Faq::query()
                ->whereIn('category_id', $categoryIds)
                ->wherePublished()
                ->get();
        } else {
            $categories = FaqCategory::query()
                ->whereIn('id', $categoryIds)
                ->with('faqs')
                ->get();
        }

        return Theme::partial('shortcodes.faqs.index', compact('shortcode', 'faqs', 'categories'));
    });

    Shortcode::setPreviewImage('faqs', Theme::asset()->url('images/shortcodes/faqs/group.png'));

    Shortcode::setAdminConfig('faqs', function (array $attributes): ShortcodeForm {
        $categories = FaqCategory::query()
            ->pluck('name', 'id')
            ->toArray();

        $categoryIds = explode(',', Arr::get($attributes, 'category_ids', ''));

        return ShortcodeForm::createFromArray($attributes)
            ->add(
                'style',
                UiSelectorField::class,
                UiSelectorFieldOption::make()
                    ->label(__('Style'))
                    ->numberItemsPerRow(2)
                    ->defaultValue('list')
                    ->selected(Arr::get($attributes, 'style', 'list'))
                    ->choices([
                        'list' => [
                            'image' => Theme::asset()->url('images/shortcodes/faqs/list.png'),
                            'label' => __('List'),
                        ],
                        'group' => [
                            'image' => Theme::asset()->url('images/shortcodes/faqs/group.png'),
                            'label' => __('Group by category'),
                        ],
                    ]),
            )
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
                    ->toArray(),
            )
            ->add(
                'description',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(__('Description'))
                    ->toArray(),
            )
            ->add(
                'category_ids',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('FAQ categories'))
                    ->choices($categories)
                    ->selected($categoryIds)
                    ->searchable()
                    ->multiple()
                    ->toArray()
            )
            ->add(
                'expand_first_time',
                CheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(__('Expand the content of the first FAQ'))
                    ->defaultValue(true)
            );
    });
}
