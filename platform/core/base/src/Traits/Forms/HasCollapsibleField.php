<?php

namespace Botble\Base\Traits\Forms;

use Illuminate\Support\Arr;

trait HasCollapsibleField
{
    public function collapsible(string $target): static
    {
        $this->attributes([
            'data-bb-toggle' => 'collapse',
            'data-bb-target' => sprintf('[data-bb-trigger=%s]', $target),
        ]);

        return $this;
    }

    public function collapseTrigger(string $trigger, array|string|int $value, bool $isShow = true): static
    {
        $this->wrapperAttributes([
            'data-bb-trigger' => $trigger,
            'data-bb-value' => $value,
            'style' => trim(sprintf(
                '%s; %s',
                Arr::get($this->getWrapperAttributes(), 'style'),
                $isShow ? '' : 'display: none;'
            )),
        ]);

        return $this;
    }
}
