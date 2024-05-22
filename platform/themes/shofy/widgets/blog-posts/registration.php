<?php

if (is_plugin_active('blog')) {
    require_once __DIR__ . '/blog-posts.php';

    register_widget(BlogPostsWidget::class);
}
