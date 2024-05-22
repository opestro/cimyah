<?php

namespace Botble\AuditLog\Listeners;

use Botble\AuditLog\Events\AuditHandlerEvent;
use Botble\AuditLog\Facades\AuditLog;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\BaseHelper;
use Exception;

class UpdatedContentListener
{
    public function handle(UpdatedContentEvent $event): void
    {
        try {
            if ($event->data->getKey()) {
                $model = $event->screen;

                event(new AuditHandlerEvent(
                    $model,
                    'updated',
                    $event->data->getKey(),
                    AuditLog::getReferenceName($model, $event->data),
                    'primary'
                ));
            }
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
