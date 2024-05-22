@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    @if ($order->token)
        <x-core::alert
            type="info"
            icon="ti ti-shopping-cart"
            :title="trans('plugins/ecommerce::order.incomplete_order_description_1')"
        >
            <x-core::form.label class="mt-3">
                {{ trans('plugins/ecommerce::order.incomplete_order_description_2') }}
            </x-core::form.label>

            <x-core::form.text-input
                name="url"
                value="{{ route('public.checkout.recover', $order->token) }}"
                onclick="this.focus(); this.select();"
            >
                @if ($hasEmail = ($order->user->email || $order->address->email))
                    <x-slot:append>
                        <x-core::button
                            class="btn-trigger-send-order-recover-modal"
                            data-action="{{ route('orders.send-order-recover-email', $order->id) }}"
                        >
                            {{ trans('plugins/ecommerce::order.send_an_email_to_recover_this_order') }}
                        </x-core::button>
                    </x-slot:append>
                @endif
            </x-core::form.text-input>

            @if (! $hasEmail)
                <p class="mb-0 text-warning">{{ trans('plugins/ecommerce::order.cannot_send_order_recover_to_mail') }}</p>
            @endif
        </x-core::alert>
    @endif

    <div class="row row-cards">
        <div class="col-md-8">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ trans('plugins/ecommerce::order.order_information') }}
                    </x-core::card.title>
                </x-core::card.header>
                <x-core::card.body>
                    <x-core::table>
                        <x-core::table.body>
                        @php
                            $order->load(['products.product']);
                        @endphp

                        @foreach ($order->products as $orderProduct)
                            @php
                                $product = $orderProduct->product;
                            @endphp

                            @if ($product && $product->original_product)
                                <x-core::table.body.row>
                                    <x-core::table.body.cell style="width: 80px">
                                        <img
                                            src="{{ RvMedia::getImageUrl($orderProduct->product_image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                            alt="{{ $product->name }}"
                                        >
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        @if ($product->original_product->id)
                                            <a
                                                href="{{ route('products.edit', $product->original_product->id) }}"
                                                title="{{ $orderProduct->product_name }}"
                                                target="_blank"
                                            >
                                                {{ $orderProduct->product_name }}
                                            </a>
                                        @else
                                            <span>{{ $orderProduct->product_name }}</span>
                                        @endif
                                        <p class="small my-1">
                                            {{ $product->variation_attributes }}
                                        </p>
                                        @if ($product->sku)
                                            <p class="mb-0">
                                                {{ trans('plugins/ecommerce::order.sku') }}:
                                                <strong>{{ $product->sku }}</strong>
                                            </p>
                                        @endif
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell class="text-end">
                                        {{ format_price($orderProduct->price) }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell class="text-center">
                                        x
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell class="text-start">
                                        {{ $orderProduct->qty }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell class="text-end fw-medium">
                                        {{ format_price($orderProduct->price * $orderProduct->qty) }}
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>
                            @endif
                        @endforeach
                        </x-core::table.body>
                    </x-core::table>

                    <div class="offset-md-6">
                        <x-core::table :striped="false" :hover="false" class="table-borderless">
                            <x-core::table.body>
                                <x-core::table.body.row>
                                    <x-core::table.body.cell class="text-end">
                                        {{ trans('plugins/ecommerce::order.quantity') }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell class="text-end fw-medium">
                                        {{ number_format($order->products->sum('qty')) }}
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>
                                <x-core::table.body.row>
                                    <x-core::table.body.cell class="text-end">
                                        {{ trans('plugins/ecommerce::order.sub_amount') }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell class="text-end fw-medium">
                                        {{ format_price($order->sub_total) }}
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>
                                <x-core::table.body.row>
                                    <x-core::table.body.cell class="text-end color-subtext mt10">
                                        <p class="mb-0">{{ trans('plugins/ecommerce::order.discount') }}</p>
                                        @if ($order->coupon_code)
                                            <small class="mt-1">{!! BaseHelper::clean(
                                                trans('plugins/ecommerce::order.coupon_code', ['code' => Html::tag('strong', $order->coupon_code)->toHtml()])
                                            ) !!}</small>
                                        @elseif ($order->discount_description)
                                            <small class="mt-1">{{ $order->discount_description }}</small>
                                        @endif
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell class="text-end fw-medium">
                                        {{ format_price($order->discount_amount) }}
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>
                                <x-core::table.body.row>
                                    <x-core::table.body.cell class="text-end">
                                        <p class="mb-1">{{ trans('plugins/ecommerce::order.shipping_fee') }}</p>
                                        <p class="mb-0 small">{{ $order->shipping_method_name }}</p>
                                        <p class="mb-0 small">{{ $weight }} {{ ecommerce_weight_unit(true) }}</p>
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell class="text-end fw-medium">
                                        {{ format_price($order->shipping_amount) }}
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>
                                @if (EcommerceHelper::isTaxEnabled())
                                    <x-core::table.body.row>
                                        <x-core::table.body.cell class="text-end">
                                            {{ trans('plugins/ecommerce::order.tax') }}
                                        </x-core::table.body.cell>
                                        <x-core::table.body.cell class="text-end fw-medium">
                                            {{ format_price($order->tax_amount) }}
                                        </x-core::table.body.cell>
                                    </x-core::table.body.row>
                                @endif
                                <x-core::table.body.row>
                                    <x-core::table.body.cell class="text-end">
                                        <p class="mb-0">{{ trans('plugins/ecommerce::order.total_amount') }}</p>
                                        @if (is_plugin_active('payment') && $order->payment->id)
                                            <a
                                                href="{{ route('payment.show', $order->payment->id) }}"
                                                target="_blank"
                                            >
                                                {{ $order->payment->payment_channel->label() }}
                                            </a>
                                        @endif
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell class="text-end fw-medium">
                                        @if (is_plugin_active('payment') && $order->payment->id)
                                            <a
                                                href="{{ route('payment.show', $order->payment->id) }}"
                                                target="_blank"
                                            >
                                                {{ format_price($order->amount) }}
                                            </a>
                                        @else
                                            {{ format_price($order->amount) }}
                                        @endif
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>

                                {!! apply_filters('ecommerce_admin_order_extra_info', null, $order) !!}
                            </x-core::table.body>
                        </x-core::table>
                    </div>

                    <form action="{{ route('orders.edit', $order->id) }}">
                        <x-core::form.textarea
                            :label="trans('plugins/ecommerce::order.order_note')"
                            class="textarea-auto-height"
                            name="description"
                            :placeholder="trans('plugins/ecommerce::order.order_note_placeholder')"
                            rows="2"
                            :value="$order->description"
                        />
                        <div class="text-end">
                            <x-core::button class="btn-update-order">
                                {{ trans('plugins/ecommerce::order.save_note') }}
                            </x-core::button>
                        </div>
                    </form>
                </x-core::card.body>
                <x-core::card.footer class="text-end">
                    <x-core::button
                        color="primary"
                        class="btn-mark-order-as-completed-modal"
                        data-action="{{ route('orders.mark-as-completed', $order->id) }}"
                        icon="ti ti-check"
                    >
                        {{ trans('plugins/ecommerce::order.mark_as_completed.name') }}
                    </x-core::button>
                </x-core::card.footer>
            </x-core::card>
        </div>

        <div class="col-md-4">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ trans('plugins/ecommerce::order.customer_label') }}
                    </x-core::card.title>
                </x-core::card.header>

                <x-core::card.body class="p-0">
                    <div class="p-3">
                        <div class="mb-3">
                            <span class="avatar avatar-lg avatar-rounded" style="background-image: url('{{ $order->user->id ? $order->user->avatar_url : $order->address->avatar_url }}')"></span>
                        </div>

                        <p class="mb-1 fw-semibold">{{ $order->user->name ?: $order->address->name }}</p>

                        @if ($order->user->id)
                            <p class="mb-1">
                                <x-core::icon name="ti ti-inbox" />
                                {{ $order->user->orders()->count() }}
                                {{ trans('plugins/ecommerce::order.orders') }}
                            </p>
                        @endif

                        <p class="mb-1">
                            <a href="mailto:{{ $email = ($order->user->email ?: $order->address->email) }}">
                                {{ $email }}
                            </a>
                        </p>

                        @if ($order->user->id)
                            <p class="mb-1">{{ trans('plugins/ecommerce::order.have_an_account_already') }}</p>
                        @else
                            <p class="mb-1">{{ trans('plugins/ecommerce::order.dont_have_an_account_yet') }}</p>
                        @endif
                    </div>

                    <div class="hr my-1"></div>

                    <div class="p-3">
                        <h4>{{ trans('plugins/ecommerce::order.shipping_address') }}</h4>

                        <dl class="mb-0">
                            <dd>{{ $order->address->name }}</dd>
                            <dd>
                                <a href="tel:{{ $phone = $order->address->phone }}">
                                    <x-core::icon name="ti ti-phone" />
                                    <span dir="ltr">{{ $phone }}</span>
                                </a>
                            </dd>
                            <dd>{{ $order->address->full_address }}</dd>
                            <dd>
                                <a
                                    href="https://maps.google.com/?q={{ $order->address->full_address }}"
                                    target="_blank"
                                >
                                    {{ trans('plugins/ecommerce::order.see_maps') }}
                                </a>
                            </dd>
                        </dl>
                    </div>

                    @if ($order->referral()->count())
                        <div class="hr my-1"></div>

                        <div class="p-3">
                            <h4>{{ trans('plugins/ecommerce::order.referral') }}</h4>
                        </div>

                        <dl class="mb-0">
                            @foreach (['ip', 'landing_domain', 'landing_page', 'landing_params', 'referral', 'gclid', 'fclid', 'utm_source', 'utm_campaign', 'utm_medium', 'utm_term', 'utm_content', 'referrer_url', 'referrer_domain'] as $field)
                                @if ($order->referral->{$field})
                                    <dt>{{ trans("plugins/ecommerce::order.referral_data.$field") }}</dt>
                                    <dd>{{ $order->referral->{$field} }}</dd>
                                @endif
                            @endforeach
                        </dl>
                    @endif
                </x-core::card.body>
            </x-core::card>
        </div>
    </div>
@stop

@push('footer')
    <x-core::modal.action
        id="send-order-recover-email-modal"
        type="info"
        :title="trans('plugins/ecommerce::order.notice_about_incomplete_order')"
        :submit-button-label="trans('plugins/ecommerce::order.send')"
        :submit-button-attrs="['id' => 'confirm-send-recover-email-button']"
    >
        <x-slot:description>
            {!! trans('plugins/ecommerce::order.notice_about_incomplete_order_description', [
                'email' => $order->user->id ? $order->user->email : $order->address->email,
            ]) !!}
        </x-slot:description>
    </x-core::modal.action>

    <x-core::modal.action
        id="mark-order-as-completed-modal"
        type="info"
        :title="trans('plugins/ecommerce::order.mark_as_completed.modal_title')"
        :description="trans('plugins/ecommerce::order.mark_as_completed.modal_description')"
        :submit-button-attrs="['id' => 'confirm-mark-as-completed-button']"
        :submit-button-label="trans('plugins/ecommerce::order.mark_as_completed.name')"
    />
@endpush
