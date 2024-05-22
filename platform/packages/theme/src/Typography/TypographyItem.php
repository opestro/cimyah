<?php

namespace Botble\Theme\Typography;

class TypographyItem
{
    protected string $name;

    protected string $label;

    protected string|float $default;

    protected array $fontWeights = [];

    public function __construct(
        string $name,
        string $label,
        string|float $default,
        array $fontWeights = [],
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->default = $default;
        $this->fontWeights = $fontWeights;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDefault(): string|float
    {
        return $this->default;
    }

    public function getFontWeights(): array
    {
        return $this->fontWeights;
    }
}
