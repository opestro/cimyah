<?php

namespace Botble\Menu\Forms;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Menu\Http\Requests\MenuRequest;
use Botble\Menu\Models\Menu;

class MenuForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addStyles('jquery-nestable')
            ->addScripts('jquery-nestable')
            ->addScriptsDirectly('vendor/core/packages/menu/js/menu.js')
            ->addStylesDirectly('vendor/core/packages/menu/css/menu.css');

        $this
            ->model(Menu::class)
            ->setFormOption('class', 'form-save-menu')
            ->setValidatorClass(MenuRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required()->maxLength(120)->toArray())
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->addMetaBoxes([
                'structure' => [
                    'wrap' => false,
                    'content' => function () {
                        return view('packages/menu::menu-structure', [
                            'menu' => $this->getModel(),
                            'locations' => $this->getModel()->getKey() ? $this->getModel()->locations()->pluck('location')->all() : [],
                        ])->render();
                    },
                ],
            ])
            ->setBreakFieldPoint('status');
    }
}
