@if (setting('admin_logo') || config('core.base.general.logo'))
    <a href="{{ route('dashboard.index') }}">
        <img
            src="{{ setting('admin_logo') ? RvMedia::getImageUrl(setting('admin_logo')) : url(config('core.base.general.logo')) }}"
            width="110"
            height="32"
            alt="{{ setting('admin_title', config('core.base.general.base_name')) }}"
            class="navbar-brand-image"
        >
    </a>
@endif
