<?php

namespace Botble\Setting\Forms;

use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Setting\Http\Requests\CacheSettingRequest;

class CacheSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setUrl(route('settings.cache.update'))
            ->setSectionTitle(trans('core/setting::setting.cache.title'))
            ->setSectionDescription(trans('core/setting::setting.cache.description'))
            ->setValidatorClass(CacheSettingRequest::class)
            ->add('enable_cache', 'html', [
                'html' => view('core/setting::partials.cache.cache-fields')->render(),
            ])
            ->add('cache_admin_menu_enable', OnOffCheckboxField::class, [
                'label' => trans('core/setting::setting.cache.form.cache_admin_menu'),
                'value' => setting('cache_admin_menu_enable', false),
            ])
            ->add('enable_site_map_cache', HtmlField::class, [
                'html' => view('core/setting::partials.cache.cache-site-map-fields')->render(),
            ]);
    }
}
