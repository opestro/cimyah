@extends(EcommerceHelper::viewPath('customers.master'))

@php
    $tabs = [
        'profile' => [
            'title' => __('Profile'),
            'route' => 'customer.edit-account',
        ],
        'change-password' => [
            'title' => __('Change Password'),
            'route' => 'customer.change-password',
        ],
    ];
@endphp

@section('content')
    <ul class="nav nav-tabs nav-fill" role="tablist">
        @foreach($tabs as $key => $tab)
            <li class="nav-item" role="presentation">
                <a
                    href="{{ route($tab['route']) }}"
                    @class(['nav-link', 'active' => Route::is($tab['route'])])
                    role="tab"
                    aria-controls="{{ $key }}-tab-pane"
                    aria-selected="{{ Route::is($tab['route']) ? 'true' : 'false' }}"
                >
                    {{ $tab['title'] }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active pt-4" role="tabpanel">
            @yield('account-content')
        </div>
    </div>
@endsection
