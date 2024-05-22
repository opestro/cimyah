<?php

namespace Botble\Setting\Forms;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\Fields\TextField;
use Botble\Setting\Http\Requests\EmailSettingRequest;

class EmailSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        Assets::addScriptsDirectly('vendor/core/core/setting/js/email.js');

        $this
            ->setUrl(route('settings.email.update'))
            ->setSectionTitle(trans('core/setting::setting.panel.email'))
            ->setSectionDescription(trans('core/setting::setting.panel.email_description'))
            ->contentOnly()
            ->setActionButtons(view('core/setting::partials.email.action-buttons', ['form' => $this->getFormOption('id')])->render())
            ->setValidatorClass(EmailSettingRequest::class)
            ->add('mailer', 'html', [
                'html' => view('core/setting::partials.email.email-fields'),
            ])
            ->add('email_from_name', TextField::class, [
               'label' => trans('core/setting::setting.email.sender_name'),
               'value' => old('email_from_name', setting('email_from_name', config('mail.from.name'))),
                'attr' => [
                    'placeholder' => trans('core/setting::setting.email.sender_name_placeholder'),
                    'data-counter' => 60,
                ],
            ])
            ->add('email_from_address', TextField::class, [
                'label' => trans('core/setting::setting.email.sender_email'),
                'value' => old('email_from_address', setting('email_from_address', config('mail.from.address'))),
                'attr' => [
                    'placeholder' => 'admin@example.com',
                    'data-counter' => 60,
                ],
                'wrapper' => [
                    'class' => 'mb-0',
                ],
            ]);
    }
}
