<?php

namespace Botble\Page\Forms;

use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Page\Http\Requests\PageRequest;
use Botble\Page\Models\Page;
use Botble\Page\Supports\Template;

class PageForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Page::class)
            ->setValidatorClass(PageRequest::class)
            ->hasTabs()
            ->add('name', TextField::class, NameFieldOption::make()->maxLength(120)->required()->toArray())
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->toArray())
            ->add('content', EditorField::class, ContentFieldOption::make()->allowedShortcodes()->toArray())
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->when(Template::getPageTemplates(), function (PageForm $form, array $templates) {
                return $form
                    ->add(
                        'template',
                        SelectField::class,
                        SelectFieldOption::make()
                            ->label(trans('core/base::forms.template'))
                            ->required()
                            ->choices($templates)
                            ->toArray()
                    );
            })
            ->add('image', MediaImageField::class)
            ->setBreakFieldPoint('status');
    }
}
