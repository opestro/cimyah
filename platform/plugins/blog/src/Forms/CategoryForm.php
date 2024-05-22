<?php

namespace Botble\Blog\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\IsDefaultFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Blog\Http\Requests\CategoryRequest;
use Botble\Blog\Models\Category;

class CategoryForm extends FormAbstract
{
    public function setup(): void
    {
        $list = get_categories(['condition' => []]);

        $categories = [];
        foreach ($list as $row) {
            if ($this->getModel() && ($this->model->id === $row->id || $this->model->id === $row->parent_id)) {
                continue;
            }

            $categories[$row->id] = $row->indent_text . ' ' . $row->name;
        }

        $categories = [0 => trans('plugins/blog::categories.none')] + $categories;

        $maxOrder = Category::query()
            ->whereIn('parent_id', [0, null])
            ->orderByDesc('order')
            ->value('order');

        $this
            ->model(Category::class)
            ->setValidatorClass(CategoryRequest::class)
            ->add('order', 'hidden', [
                'value' => $maxOrder + 1,
            ])
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add(
                'parent_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('core/base::forms.parent'))
                    ->choices($categories)
                    ->searchable()
                    ->toArray()
            )
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->toArray())
            ->add('is_default', OnOffField::class, IsDefaultFieldOption::make()->toArray())
            ->add(
                'icon',
                $this->getFormHelper()->hasCustomField('themeIcon') ? 'themeIcon' : TextField::class,
                TextFieldOption::make()
                    ->label(trans('core/base::forms.icon'))
                    ->placeholder('Ex: fa fa-home')
                    ->maxLength(120)
                    ->toArray()
            )
            ->add(
                'is_featured',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('core/base::forms.is_featured'))
                    ->defaultValue(false)
                    ->toArray()
            )
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->setBreakFieldPoint('status');
    }
}
