<?php

use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Shortcode\Compilers\Shortcode as ShortcodeCompiler;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;
use Illuminate\Database\Eloquent\Collection;

app()->booted(function () {
    if (! is_plugin_active('blog')) {
        return;
    }

    Shortcode::register('blog-posts', __('Blog Posts'), __('Blog Posts'), function (ShortcodeCompiler $shortcode) {
        $limit = (int) $shortcode->limit ?: 3;

        /**
         * @var Collection $posts
         */
        $posts = match ($shortcode->type) {
            'featured' => get_featured_posts($limit),
            'popular' => get_popular_posts($limit),
            default => get_recent_posts($limit),
        };

        if ($posts->isEmpty()) {
            return null;
        }

        $posts->loadMissing(['slugable', 'categories.slugable']);

        return Theme::partial('shortcodes.blog-posts.index', compact('shortcode', 'posts'));
    });

    Shortcode::setPreviewImage('blog-posts', Theme::asset()->url('images/shortcodes/blog-posts.png'));

    Shortcode::setAdminConfig('blog-posts', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->columns()
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()->label(__('Title'))->colspan(2)->toArray(),
            )
            ->add(
                'type',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Post type'))
                    ->choices([
                        'recent' => __('Recent'),
                        'featured' => __('Featured'),
                        'popular' => __('Popular'),
                    ])
                    ->defaultValue('recent')
                    ->colspan(2)
                    ->toArray(),
            )
            ->add(
                'limit',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(__('Limit'))
                    ->helperText(__('Number of posts to show'))
                    ->defaultValue(3)
                    ->colspan(2)
                    ->toArray(),
            )
            ->add(
                'button_label',
                TextField::class,
                TextFieldOption::make()->label(__('Button Label'))->placeholder(__('Button view more label'))->toArray(
                ),
            )
            ->add(
                'button_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Button URL'))
                    ->placeholder(__('Button view more URL'))
                    ->helperText(__('Leave empty to link to the blog page'))
                    ->toArray(),
            );
    });
});
