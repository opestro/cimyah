<?php

namespace Botble\Ecommerce\Tables\Formatters;

use Botble\Table\Formatter;

class PriceFormatter implements Formatter
{
    public function format($value, $row): string
    {
        return format_price($value);
    }
}
