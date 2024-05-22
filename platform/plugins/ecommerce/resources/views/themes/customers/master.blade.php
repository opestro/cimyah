<div class="bb-customer-page card crop-avatar">
    <div class="container">
        <div class="customer-body">
            <div class="row body-border">
                <div class="col-md-3 bb-customer-sidebar-wrapper">
                    <div class="bb-profile-sidebar">
                        <div class="bb-profile-user-menu">
                            <div class="bb-customer-sidebar">
                                @if ($customer = auth('customer')->user())
                                    <a title="{{ $customer->name }}" href="{{ route('customer.overview') }}">
                                        <div class="bb-customer-sidebar-heading">
                                            <div class="d-flex gap-3">
                                                <div class="wrapper-image" style="">
                                                    {!! RvMedia::image($customer->avatar_url, $customer->name ) !!}
                                                </div>
                                                <div>
                                                    <div class="fs-6 name">{{ $customer->name }}</div>
                                                    <div class="text-muted email">{{ $customer->email }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                @foreach (DashboardMenu::getAll('customer') as $item)
                                    @continue(! $item['name'])

                                    <div @class(['bb-customer-menu-item-wrapper', 'active' => $item['active']])>
                                        <x-core::icon :name="$item['icon']" />
                                        <a
                                            class="bb-customer-menu-item"
                                            href="{{ $item['url'] }}"
                                            title="{{ $item['name'] }}"
                                        >
                                            {{ $item['name'] }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-9">
                    <div class="bb-profile-content">
                        <div class="bb-profile-header">
                            <div class="bb-profile-header-title">
                                @yield('title')
                            </div>
                        </div>
                        @yield('content')
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
