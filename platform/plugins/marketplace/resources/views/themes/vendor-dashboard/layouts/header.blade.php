{!! SeoHelper::render() !!}

@include(MarketplaceHelper::viewPath('vendor-dashboard.layouts.header-meta'))

<link
    href="{{ asset('vendor/core/plugins/marketplace/fonts/linearicons/linearicons.css') }}?v={{ MarketplaceHelper::getAssetVersion() }}"
    rel="stylesheet"
>
<link
    href="{{ asset('vendor/core/plugins/marketplace/css/marketplace.css') }}?v={{ MarketplaceHelper::getAssetVersion() }}"
    rel="stylesheet"
>

@if (BaseHelper::isRtlEnabled())
    <link href="{{ asset('vendor/core/core/base/css/core.rtl.css') }}" rel="stylesheet">

    <link
        href="{{ asset('vendor/core/plugins/marketplace/css/marketplace-rtl.css') }}?v={{ MarketplaceHelper::getAssetVersion() }}"
        rel="stylesheet"
    >
@endif

@if (File::exists($styleIntegration = Theme::getStyleIntegrationPath()))
    {!! Html::style(Theme::asset()->url('css/style.integration.css?v=' . filectime($styleIntegration))) !!}
@endif
