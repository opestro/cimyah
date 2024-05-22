<?php

namespace Botble\Ecommerce\Exceptions;

use Botble\Base\Contracts\Exceptions\IgnoringReport;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Throwable;

class ProductIsNotActivatedYetException extends BadRequestException implements IgnoringReport
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(__('Product is not published yet.') . $message, $code, $previous);
    }
}
