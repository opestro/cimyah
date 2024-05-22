@props([
    'id' => null,
    'label' => null,
    'name' => null,
    'value' => old($name),
    'helperText' => null,
    'errorKey' => $name,
    'mode' => null,
])

@php
    $id = $id ?: $name . '_' . md5($name);
    $mode = $mode === 'html' ? 'htmlmixed' : $mode;

    Assets::addStylesDirectly([
            'vendor/core/core/base/libraries/codemirror/lib/codemirror.css',
            'vendor/core/core/base/libraries/codemirror/addon/hint/show-hint.css'
        ])
        ->addScriptsDirectly([
            'vendor/core/core/base/libraries/codemirror/lib/codemirror.js',
            'vendor/core/core/base/libraries/codemirror/addon/hint/show-hint.js',
            'vendor/core/core/base/libraries/codemirror/addon/hint/anyword-hint.js',
            'vendor/core/core/base/libraries/codemirror/addon/display/autorefresh.js',
        ]);

    switch ($mode) {
        case 'htmlmixed':
            Assets::addScriptsDirectly([
                'vendor/core/core/base/libraries/codemirror/mode/htmlmixed.js',
                'vendor/core/core/base/libraries/codemirror/mode/css.js',
                'vendor/core/core/base/libraries/codemirror/mode/javascript.js',
                'vendor/core/core/base/libraries/codemirror/mode/xml.js',
                'vendor/core/core/base/libraries/codemirror/addon/hint/xml-hint.js',
                'vendor/core/core/base/libraries/codemirror/addon/hint/html-hint.js',
                'vendor/core/core/base/libraries/codemirror/addon/hint/css-hint.js',
                'vendor/core/core/base/libraries/codemirror/addon/hint/javascript-hint.js',
            ]);
            break;

        case 'css':
            Assets::addScriptsDirectly([
                'vendor/core/core/base/libraries/codemirror/mode/css.js',
                'vendor/core/core/base/libraries/codemirror/addon/hint/css-hint.js',
            ]);
            break;

        case 'javascript':
            Assets::addScriptsDirectly([
                'vendor/core/core/base/libraries/codemirror/mode/javascript.js',
                'vendor/core/core/base/libraries/codemirror/addon/hint/javascript-hint.js',
            ]);
            break;
    }
@endphp

<x-core::form-group>
    @if ($label)
        <x-core::form.label
            :label="$label"
            :for="$id"
        />
    @endif

    <textarea
        {{ $attributes->merge(['name' => $name, 'class' => 'form-control', 'id' => $id, 'data-bb-code-editor' => '', 'data-mode' => $mode]) }}
    >{{ $value ?: $slot }}</textarea>

    @if ($helperText)
        <x-core::form.helper-text class="mt-2">{!! $helperText !!}</x-core::form.helper-text>
    @endif

    <x-core::form.error :key="$errorKey" />
</x-core::form-group>
