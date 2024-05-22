<?php

namespace Botble\AuditLog;

use Botble\AuditLog\Events\AuditHandlerEvent;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class AuditLog
{
    public function handleEvent(string $screen, Model $data, string $action, string $type = 'info'): bool
    {
        if (! $data instanceof BaseModel || ! $data->getKey()) {
            return false;
        }

        event(new AuditHandlerEvent($screen, $action, $data->getKey(), $this->getReferenceName($screen, $data), $type));

        return true;
    }

    public function getReferenceName(string $screen, Model $data): string
    {
        return match ($screen) {
            USER_MODULE_SCREEN_NAME, AUTH_MODULE_SCREEN_NAME => $data->name,
            default => $data->name ?: $data->title ?: "ID: $data->id",
        };
    }
}
