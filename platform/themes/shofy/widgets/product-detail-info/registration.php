<?php

if (is_plugin_active('ecommerce')) {
    require_once __DIR__ . '/product-detail-info.php';

    register_widget(ProductDetailInfoWidget::class);
}
