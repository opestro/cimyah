<?php

namespace Botble\ACL\Forms;

use Botble\ACL\Http\Requests\PreferenceRequest;
use Botble\Base\Facades\AdminAppearance;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Supports\Language;

class PreferenceForm extends FormAbstract
{
    public function setup(): void
    {
        $languages = collect(Language::getAvailableLocales())
            ->pluck('name', 'locale')
            ->map(fn ($item, $key) => $item . ' - ' . $key)
            ->all();

        $adminAppearance = AdminAppearance::forUser($this->getModel());

        $this
            ->template('core/base::forms.form-no-wrap')
            ->setValidatorClass(PreferenceRequest::class)
            ->setMethod('PUT')
            ->when(count($languages) > 1, function (FormAbstract $form) use ($adminAppearance, $languages) {
                $form->add(
                    'locale',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(trans('core/setting::setting.admin_appearance.language'))
                        ->choices($languages)
                        ->selected($adminAppearance->getLocale())
                        ->toArray()
                );
            })
            ->add(
                'locale_direction',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('core/setting::setting.admin_appearance.form.admin_locale_direction'))
                    ->choices([
                        'ltr' => trans('core/setting::setting.locale_direction_ltr'),
                        'rtl' => trans('core/setting::setting.locale_direction_rtl'),
                    ])
                    ->selected($adminAppearance->getLocaleDirection())
                    ->toArray()
            )
            ->add(
                'theme_mode',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('core/setting::setting.admin_appearance.theme_mode'))
                    ->choices([
                        'light' => trans('core/setting::setting.admin_appearance.light'),
                        'dark' => trans('core/setting::setting.admin_appearance.dark'),
                    ])
                    ->selected($this->getModel()->getMeta('theme_mode', 'light'))
                    ->toArray()
            )
            ->setActionButtons(view('core/acl::users.profile.actions')->render());
    }
}
