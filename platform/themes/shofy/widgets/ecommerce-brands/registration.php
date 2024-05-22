<?php

if (is_plugin_active('ecommerce')) {
    require_once __DIR__ . '/ecommerce-brands.php';

    register_widget(EcommerceBrands::class);
}
