<?php

namespace Botble\Ecommerce\Forms\Fields;

use Botble\Base\Forms\FormField;

/**
 * @deprecated
 */
class CategoryMultiField extends FormField
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.tree-categories';
    }
}
