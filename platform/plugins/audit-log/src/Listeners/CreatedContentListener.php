<?php

namespace Botble\AuditLog\Listeners;

use Botble\AuditLog\Events\AuditHandlerEvent;
use Botble\AuditLog\Facades\AuditLog;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Facades\BaseHelper;
use Exception;

class CreatedContentListener
{
    public function handle(CreatedContentEvent $event): void
    {
        try {
            if ($event->data->getKey()) {
                $model = $event->screen;

                event(new AuditHandlerEvent(
                    $model,
                    'created',
                    $event->data->getKey(),
                    AuditLog::getReferenceName($model, $event->data),
                    'info'
                ));
            }
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
