<?php

if (is_plugin_active('blog')) {
    require_once __DIR__ . '/blog-tags.php';

    register_widget(BlogTagsWidget::class);
}
