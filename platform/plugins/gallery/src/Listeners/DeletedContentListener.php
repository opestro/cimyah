<?php

namespace Botble\Gallery\Listeners;

use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Facades\BaseHelper;
use Botble\Gallery\Facades\Gallery;
use Exception;

class DeletedContentListener
{
    public function handle(DeletedContentEvent $event): void
    {
        try {
            Gallery::deleteGallery($event->data);
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
