<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\MetaBox;
use Botble\Ecommerce\Models\Product;
use Botble\Faq\FaqSupport;

class SaveProductFaqListener
{
    public function handle(CreatedContentEvent|UpdatedContentEvent $event): void
    {
        $request = $event->request;
        $model = $event->data;

        if (! is_object($model) || ! $model instanceof Product) {
            return;
        }

        if ($request->has('content') && $request->has('faq_schema_config')) {
            (new FaqSupport())->saveConfigs($model, $request->input('faq_schema_config'));
        }

        MetaBox::saveMetaBoxData($model, 'faq_ids', $request->input('selected_existing_faqs', []));
    }
}
