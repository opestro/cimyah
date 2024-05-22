<?php

if (is_plugin_active('newsletter')) {
    require_once __DIR__ . '/newsletter.php';

    register_widget(NewsletterWidget::class);
}
