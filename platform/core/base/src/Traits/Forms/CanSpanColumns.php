<?php

namespace Botble\Base\Traits\Forms;

trait CanSpanColumns
{
    protected int $colspan = 0;

    public function colspan(int $colspan): static
    {
        $this->colspan = $colspan;

        return $this;
    }

    public function getColspan(): int
    {
        return $this->colspan;
    }

    public function getColumnSpan(int|string|null $breakpoint = null): array|int|string|null
    {
        $columnSpan = [];
        $span = $this->getOption('colspan');

        /**
         * @var \Botble\Base\Forms\FormAbstract $parent
         */
        $parent = $this->getParent();

        if ($span === 'full') {
            $parentSpan = $parent->getColumns();

            if ($breakpoint !== null) {
                $span = $parentSpan[$breakpoint] ?? null;
            }
        }

        if (! is_array($span)) {
            $span = [
                'lg' => ceil(12 / ((int) $parent->getColumns('lg')) * $span),
            ];
        }

        $columnSpan = [
            ...$columnSpan,
            ...$span,
        ];

        if ($breakpoint !== null) {
            return $columnSpan[$breakpoint] ?? null;
        }

        return array_map(function ($value) use ($span) {
            return $value * $span;
        }, $parent->getColumns());
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        return view('core/base::forms.columns.column-span', [
            'field' => $this,
            'html' => parent::render($options, $showLabel, $showField, $showError),
        ]);
    }
}
