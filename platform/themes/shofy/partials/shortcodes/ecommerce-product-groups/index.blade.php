@php
    Theme::asset()->container('footer')->writeContent('quick-view-modal', view(Theme::getThemeNamespace('views.ecommerce.includes.quick-view-modal')));
    Theme::asset()->container('footer')->writeContent('quick-shop-modal', view(EcommerceHelper::viewPath('includes.quick-shop-modal')));
    Theme::asset()->add('lightgallery-css', 'vendor/core/plugins/ecommerce/libraries/lightgallery/css/lightgallery.min.css');
    Theme::asset()->container('footer')->add('lightgallery-js', 'vendor/core/plugins/ecommerce/libraries/lightgallery/js/lightgallery.min.js', ['jquery']);
@endphp

{!! Theme::partial(
    "shortcodes.ecommerce-product-groups.$style",
    compact('shortcode', 'productTabs', 'selectedTabs', 'groups', 'style')
) !!}
