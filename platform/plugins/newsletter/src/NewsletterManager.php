<?php

namespace Botble\Newsletter;

use Botble\Base\Facades\AdminHelper;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\EmailFieldOption;
use Botble\Base\Forms\Fields\CheckboxField;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Newsletter\Contracts\Factory;
use Botble\Newsletter\Drivers\MailChimp;
use Botble\Newsletter\Drivers\SendGrid;
use Botble\Newsletter\Forms\Fronts\NewsletterForm;
use Botble\Theme\Events\RenderingThemeOptionSettings;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Facades\ThemeOption;
use Illuminate\Support\Manager;
use InvalidArgumentException;

class NewsletterManager extends Manager implements Factory
{
    protected function createMailChimpDriver(): MailChimp
    {
        return new MailChimp(
            setting('newsletter_mailchimp_api_key'),
            setting('newsletter_mailchimp_list_id')
        );
    }

    protected function createSendGridDriver(): SendGrid
    {
        return new SendGrid(
            setting('newsletter_sendgrid_api_key'),
            setting('newsletter_sendgrid_list_id')
        );
    }

    public function getDefaultDriver(): string
    {
        throw new InvalidArgumentException('No email marketing provider was specified.');
    }

    public function registerNewsletterPopup(bool $keepHtmlDomOnClose = false): void
    {
        app('events')->listen(RenderingThemeOptionSettings::class, function () {
            ThemeOption::setSection([
                'title' => __('Newsletter Popup'),
                'id' => 'opt-text-subsection-newsletter-popup',
                'subsection' => true,
                'icon' => 'ti ti-mail-opened',
                'fields' => [
                    [
                        'id' => 'newsletter_popup_enable',
                        'type' => 'onOff',
                        'label' => __('Enable Newsletter Popup'),
                        'attributes' => [
                            'name' => 'newsletter_popup_enable',
                            'value' => false,
                        ],
                    ],
                    [
                        'id' => 'newsletter_popup_image',
                        'type' => 'mediaImage',
                        'label' => __('Popup image'),
                        'attributes' => [
                            'name' => 'newsletter_popup_image',
                        ],
                    ],
                    [
                        'id' => 'newsletter_popup_title',
                        'type' => 'text',
                        'label' => __('Popup title'),
                        'attributes' => [
                            'name' => 'newsletter_popup_title',
                            'value' => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'id' => 'newsletter_popup_subtitle',
                        'type' => 'text',
                        'label' => __('Popup subtitle'),
                        'attributes' => [
                            'name' => 'newsletter_popup_subtitle',
                            'value' => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'id' => 'newsletter_popup_description',
                        'type' => 'textarea',
                        'label' => __('Popup description'),
                        'attributes' => [
                            'name' => 'newsletter_popup_description',
                            'value' => null,
                            'options' => [
                                'class' => 'form-control',
                                'rows' => 2,
                            ],
                        ],
                    ],
                    [
                        'id' => 'newsletter_popup_delay',
                        'type' => 'number',
                        'label' => __('Popup delay (seconds)'),
                        'attributes' => [
                            'name' => 'newsletter_popup_delay',
                            'value' => 5,
                            'options' => [
                                'class' => 'form-control',
                                'min' => 0,
                            ],
                        ],
                        'helper' => __('Set the delay time to show the popup after the page is loaded. Set 0 to show the popup immediately.'),
                    ],
                ],
            ]);
        });

        if (
            is_plugin_active('newsletter')
            && theme_option('newsletter_popup_enable', false)
            && ($keepHtmlDomOnClose || ! isset($_COOKIE['newsletter_popup']))
            && ! AdminHelper::isInAdmin()
        ) {

            $ignoredBots = [
                'googlebot',        // Googlebot
                'bingbot',          // Microsoft Bingbot
                'slurp',            // Yahoo! Slurp
                'ia_archiver',      // Alexa
                'Chrome-Lighthouse', // Google Lighthouse
            ];

            if (in_array(strtolower(request()->userAgent()), $ignoredBots)) {
                return;
            }

            Theme::asset()
                ->add('newsletter', asset('vendor/core/plugins/newsletter/css/newsletter.css'));

            Theme::asset()
                ->container('footer')
                ->add('newsletter', asset('vendor/core/plugins/newsletter/js/newsletter.js'), ['jquery']);

            add_filter(THEME_FRONT_BODY, function (?string $html): string {
                $newsletterForm = NewsletterForm::create()
                    ->remove(['wrapper_before', 'wrapper_after', 'email'])
                    ->addBefore(
                        'submit',
                        'email',
                        EmailField::class,
                        EmailFieldOption::make()
                            ->label(__('Email Address'))
                            ->maxLength(-1)
                            ->placeholder(__('Enter Your Email'))
                            ->required()
                            ->toArray()
                    )
                    ->addAfter(
                        'submit',
                        'dont_show_again',
                        CheckboxField::class,
                        CheckboxFieldOption::make()
                            ->label(__("Don't show this popup again"))
                            ->value(false)
                            ->toArray()
                    );

                return $html . view('plugins/newsletter::partials.newsletter-popup', compact('newsletterForm'));
            });
        }
    }
}
