<?php

namespace Botble\Ecommerce\Forms\Fronts\Auth;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Theme\Facades\Theme;
use Botble\Theme\FormFront;

abstract class AuthForm extends FormFront
{
    public function setup(): void
    {
        Theme::asset()->add('auth-css', 'vendor/core/plugins/ecommerce/css/front-auth.css', version: get_cms_version());

        Theme::addBodyAttributes(['id' => 'page-auth']);

        $this
            ->contentOnly()
            ->template('plugins/ecommerce::customers.forms.auth');
    }

    public function submitButton(string $label, string $icon = null, string $iconPosition = 'append'): static
    {
        $iconHtml = $icon ? BaseHelper::renderIcon($icon) : '';

        return $this
            ->add('openButtonWrap', HtmlField::class, [
                'html' => '<div class="d-grid">',
            ])
            ->add('submit', 'submit', [
                'label' =>
                    ($icon && $iconPosition === 'prepend' ? $iconHtml : '')
                    . $label
                    . ($icon && $iconPosition === 'append' ? $iconHtml : ''),
                'attr' => [
                    'class' => 'btn btn-primary btn-auth-submit',
                ],
            ])
            ->add('closeButtonWrap', HtmlField::class, [
                'html' => '</div>',
            ]);
    }

    public function banner(string $banner): static
    {
        return $this->setFormOption('banner', $banner);
    }

    public function icon(string $icon): static
    {
        return $this->setFormOption('icon', $icon);
    }

    public function heading(string $heading): static
    {
        return $this->setFormOption('heading', $heading);
    }

    public function description(string $description): static
    {
        return $this->setFormOption('description', $description);
    }

    public function ignoreBaseTemplate(): static
    {
        $this
            ->banner('')
            ->icon('')
            ->heading('')
            ->description('')
            ->contentOnly();

        return $this;
    }
}
