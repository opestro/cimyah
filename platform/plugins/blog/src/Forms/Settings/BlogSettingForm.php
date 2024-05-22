<?php

namespace Botble\Blog\Forms\Settings;

use Botble\Blog\Http\Requests\Settings\BlogSettingRequest;
use Botble\Setting\Forms\SettingForm;

class BlogSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/blog::base.settings.title'))
            ->setSectionDescription(trans('plugins/blog::base.settings.description'))
            ->setValidatorClass(BlogSettingRequest::class)
            ->add('blog_setting', 'html', [
                'html' => view('plugins/blog::partials.blog-post-schema-fields'),
                'wrapper' => [
                    'class' => 'mb-0',
                ],
            ]);
    }
}
