<div class="nav-item dropdown d-none d-md-flex me-3">
    <button
        class="nav-link px-0"
        data-bs-toggle="dropdown"
        type="button"
        tabindex="-1"
    >
        <x-core::icon name="ti ti-shopping-cart" />
        <span class="badge bg-red text-red-fg badge-pill">{{ number_format($orders->total()) }}</span>
    </button>

    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card" >
        <x-core::card>
            <x-core::card.header>
                <h3 class="card-title">{!! trans('plugins/ecommerce::order.new_order_notice', ['count' => $orders->total()]) !!}</h3>
                <div class="card-actions">
                    <a href="{{ route('orders.index') }}">{{ trans('plugins/ecommerce::order.view_all') }}</a>
                </div>
            </x-core::card.header>
            <div
                class="list-group list-group-flush list-group-hoverable overflow-auto"
                style="max-height: 35rem"
            >
                @foreach ($orders as $order)
                    <a href="{{ route('orders.edit', $order->id) }}" class="text-decoration-none">
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-auto">
                                    <img
                                        class="avatar"
                                        src="{{ $order->user->id ? $order->user->avatar_url : $order->address->avatar_url }}"
                                        alt="{{ $order->address->name }}"
                                    >
                                </div>
                                <div class="col align-items-center">
                                    <p class="text-truncate mb-2">
                                        {{ $order->address->name ?: $order->user->name }}
                                        <time
                                            class="small text-muted"
                                            title="{{ $createdAt = BaseHelper::formatDateTime($order->created_at) }}"
                                            datetime="{{ $createdAt }}"
                                        >
                                            {{ $createdAt }}
                                        </time>
                                    </p>
                                    <p class="text-secondary text-truncate mt-n1 mb-0">
                                        {{ implode(' - ', [$order->address->phone, ($order->address->email ?: $order->user->email)]) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </x-core::card>
    </div>
</div>
