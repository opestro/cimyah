<?php

namespace Botble\Table\BulkChanges;

use Botble\Table\Abstracts\TableBulkChangeAbstract;
use Closure;

class SelectBulkChange extends TableBulkChangeAbstract
{
    protected bool $searchable = false;

    protected Closure|array $choices;

    protected Closure $callback;

    public static function make(array $data = []): static
    {
        return parent::make()
            ->type('customSelect')
            ->validate(['required', 'string']);
    }

    public function searchable(bool $searchable = true): static
    {
        $this->searchable = $searchable;

        return $this;
    }

    public function choices(Closure|callable|array $choices): static
    {
        if (is_callable($choices)) {
            $this->choices = [];
            $this->callback = $choices;
        } else {
            $this->choices = $choices;
        }

        return $this;
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if (isset($this->callback)) {
            $data['callback'] = $this->callback;
        } else {
            $data['choices'] = $this->choices;
        }

        if ($this->searchable) {
            $data['type'] = 'select-search';
        }

        return $data;
    }
}
