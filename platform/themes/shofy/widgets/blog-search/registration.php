<?php

if (is_plugin_active('blog')) {
    require_once __DIR__ . '/blog-search.php';

    register_widget(BlogSearchWidget::class);
}
