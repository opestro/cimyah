<?php

if (is_plugin_active('blog')) {
    require_once __DIR__ . '/blog-categories.php';

    register_widget(BlogCategoriesWidget::class);
}
