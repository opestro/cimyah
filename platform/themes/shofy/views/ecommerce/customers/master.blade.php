@php
    Theme::set('breadcrumbStyle', 'none');
    Theme::layout('full-width');
@endphp

<section class="profile__area pt-60 pb-120">
    <div class="container">
        <div class="profile__inner p-relative">
            <div class="row">
                <div class="col-xxl-4 col-lg-4">
                    <div class="profile__tab me-40">
                        <nav>
                            <div class="nav nav-tabs tp-tab-menu flex-column" id="profile-tab" role="tablist">
                                @foreach (DashboardMenu::getAll('customer') as $item)
                                    @continue(! $item['name'])

                                    <a href="{{ $item['url'] }}" @class(['nav-link', 'active' => $item['active']]) id="nav-profile-tab" role="tab" aria-controls="nav-profile" aria-selected="false">
                                        <span><x-core::icon :name="$item['icon']" /></span>
                                        {{ $item['name'] }}
                                    </a>
                                @endforeach
                                <span id="marker-vertical" class="tp-tab-line d-none d-sm-inline-block"></span>
                            </div>
                        </nav>
                    </div>
                </div>
                <div class="col-xxl-8 col-lg-8">
                    <div class="profile__tab-content">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
