@if($categories->isNotEmpty())
    @include(Theme::getThemeNamespace("widgets.product-categories.templates.styles.$style"))
@endif
