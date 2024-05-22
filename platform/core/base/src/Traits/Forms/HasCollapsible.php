<?php

namespace Botble\Base\Traits\Forms;

use Botble\Base\Forms\FormCollapse;

trait HasCollapsible
{
    public function addCollapsible(FormCollapse $form): static
    {
        $form->build($this);

        return $this;
    }
}
