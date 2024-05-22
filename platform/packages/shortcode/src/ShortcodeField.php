<?php

namespace Botble\Shortcode;

use Botble\Shortcode\Compilers\Shortcode as ShortcodeCompiler;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ShortcodeField
{
    public function tabs(array $fields, array $attributes = [], int $max = 20, int $min = 1): string
    {
        if (! $fields) {
            return '';
        }

        $current = (int) Arr::get($attributes, 'quantity') ?: 6;

        $selector = 'quantity_' . Str::random(20);

        $choices = range($min, $max);
        $choices = array_combine($choices, $choices);

        return view('packages/shortcode::fields.tabs', compact('fields', 'attributes', 'current', 'selector', 'choices', 'max', 'min'))->render();
    }

    public function getTabsData(array $fields, ShortcodeCompiler $shortcode): array
    {
        $quantity = min((int) $shortcode->quantity, 20);

        if (empty($shortcode->toArray()) || empty($fields) || ! $quantity) {
            return [];
        }

        $tabs = [];

        for ($i = 1; $i <= $quantity; $i++) {
            $tab = [];
            foreach ($fields as $field) {
                $tab[$field] = $shortcode->{$field . '_' . $i};
            }

            $tabs[] = $tab;
        }

        return $tabs;
    }

    public function ids(string $field, array $attributes = [], array $options = []): string
    {
        $value = Arr::get($attributes, $field);

        $value = static::parseIds($value);

        $multiple = true;

        return view('packages/shortcode::fields.select', compact(
            'field',
            'value',
            'options',
            'multiple'
        ))->render();
    }

    public function getIds(string $field, ShortcodeCompiler $shortcode): array
    {
        $value = $shortcode->{$field};

        if (empty($value) || ! is_string($value)) {
            return [];
        }

        return static::parseIds($value);
    }

    public static function parseIds(?string $value): array
    {
        if (empty($value)) {
            return [];
        }

        return explode(',', $value) ?: [];
    }
}
