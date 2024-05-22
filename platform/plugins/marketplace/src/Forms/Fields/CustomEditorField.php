<?php

namespace Botble\Marketplace\Forms\Fields;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Supports\Editor;
use Illuminate\Support\Arr;

class CustomEditorField extends TextareaField
{
    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true): string
    {
        (new Editor())->registerAssets();

        Arr::set(
            $options,
            'attr.class',
            ltrim(Arr::get($options, 'attr.class') . ' form-control editor-' . BaseHelper::getRichEditor())
        );

        return parent::render($options, $showLabel, $showField, $showError);
    }
}
