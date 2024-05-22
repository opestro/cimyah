<?php

namespace Botble\Shortcode\Forms\FieldOptions;

use Botble\Base\Contracts\BaseModel;
use Botble\Base\Forms\FormFieldOptions;
use Illuminate\Support\Arr;

class ShortcodeTabsFieldOption extends FormFieldOptions
{
    public static function make(): static
    {
        return parent::make()->max(20);
    }

    public function fields(array $fields = []): static
    {
        $this->addAttribute('fields', $fields);

        return $this;
    }

    public function attrs(array|BaseModel $attributes = []): static
    {
        if ($attributes instanceof BaseModel) {
            $attributes = $attributes->toArray();
        }

        $this->addAttribute('shortcode_attributes', $attributes);

        return $this;
    }

    public function max(int $max): static
    {
        $this->addAttribute('max', $max);

        return $this;
    }

    public function min(int $min): static
    {
        $this->addAttribute('min', $min);

        return $this;
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        foreach (['fields', 'shortcode_attributes', 'max'] as $key) {
            if (Arr::has($data['attr'], $key)) {
                $data[$key] = $data['attr'][$key];
                unset($data['attr'][$key]);
            }
        }

        if (! Arr::has($data['shortcode_attributes'], 'quantity')) {
            $data['shortcode_attributes']['quantity'] = min(Arr::get($data, 'max'), 6);
        }

        return $data;
    }
}
