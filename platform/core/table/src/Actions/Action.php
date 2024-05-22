<?php

namespace Botble\Table\Actions;

use Botble\Base\Supports\Builders\HasAttributes;
use Botble\Base\Supports\Builders\HasColor;
use Botble\Base\Supports\Builders\HasIcon;
use Botble\Base\Supports\Builders\HasUrl;
use Botble\Table\Abstracts\TableActionAbstract;
use Botble\Table\Actions\Concerns\HasAction;

class Action extends TableActionAbstract
{
    use HasAction;
    use HasAttributes;
    use HasColor;
    use HasIcon;
    use HasUrl;

    protected string $type = 'a';

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCssClass(): string
    {
        if ($this->getAttribute('class')) {
            return '';
        }

        $classes = [
            'btn',
            'btn-sm',
        ];

        if ($this->hasIcon()) {
            $classes[] = 'btn-icon';
        }

        $classes[] = $this->getColor();

        return implode(' ', $classes);
    }
}
