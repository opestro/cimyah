@if ($order)
    <div class="card mt-3">
        <div class="card-body">
            <div class="customer-order-detail">
                <div class="row">
                    <div @class(['col-12' => ! $order->address->name, 'col-md-6' => $order->address->name])>
                        <p>
                            <span class="d-inline-block me-1">{{ __('Order number') }}: </span>
                            <strong>{{ $order->code }}</strong>
                        </p>
                        <p>
                            <span class="d-inline-block me-1">{{ __('Time') }}: </span>
                            <strong>{{ $order->created_at->translatedFormat('d M Y H:i:s') }}</strong>
                        </p>
                        <p>
                            <span class="d-inline-block me-1">{{ __('Order status') }}: </span>
                            <strong class="text-info">{{ $order->status->label() }}</strong>
                        </p>
                        @if($order->cancellation_reason)
                            <p>
                                <span class="d-inline-block me-1">{{ __('Cancellation Reason') }}: </span>
                                <strong class="text-warning">{{ $order->cancellation_reason_message }}</strong>
                            </p>
                        @endif
                        @if (is_plugin_active('payment') && $order->payment->id)
                            <p>
                                <span class="d-inline-block me-1">{{ __('Payment method') }}: </span>
                                <strong class="text-info">{{ $order->payment->payment_channel->label() }}</strong>
                            </p>
                            <p>
                                <span class="d-inline-block me-1">{{ __('Payment status') }}: </span>
                                <strong class="text-info">{{ $order->payment->status->label() }}</strong>
                            </p>
                        @endif
                        @if ($order->description)
                            <p>
                                <span class="d-inline-block me-1">{{ __('Note') }}: </span>
                                <strong class="text-warning"><i>{{ $order->description }}</i></strong>
                            </p>
                        @endif
                    </div>
                    @if ($order->address->name)
                        <div class="col-md-6">
                            <p>
                                <span class="d-inline-block me-1">{{ __('Full Name') }}: </span>
                                <strong>{{ $order->address->name }}</strong>
                            </p>
                            <p>
                                <span class="d-inline-block me-1">{{ __('Phone') }}: </span>
                                <strong>{{ $order->address->phone }}</strong>
                            </p>
                            <p>
                                <span class="d-inline-block me-1">{{ __('Address') }}: </span>
                                <strong> {{ $order->address->full_address }}</strong>
                            </p>
                        </div>
                    @endif
                </div>
                <br>
                <h5 class="mb-3">{{ __('Products') }}</h5>
                <div>
                    <div class="table-responsive mb-3">
                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">{{ __('Image') }}</th>
                                    <th>{{ __('Product') }}</th>
                                    <th class="text-center">{{ __('Amount') }}</th>
                                    <th class="text-end" style="width: 100px">{{ __('Quantity') }}</th>
                                    <th class="price text-end">{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->products as $orderProduct)
                                    @php
                                        $product = get_products([
                                            'condition' => [
                                                'ec_products.id' => $orderProduct->product_id,
                                            ],
                                            'take' => 1,
                                            'select' => ['ec_products.id', 'ec_products.images', 'ec_products.name', 'ec_products.price', 'ec_products.sale_price', 'ec_products.sale_type', 'ec_products.start_date', 'ec_products.end_date', 'ec_products.sku', 'ec_products.is_variation', 'ec_products.status', 'ec_products.order', 'ec_products.created_at'],
                                        ]);
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            <img src="{{ RvMedia::getImageUrl($orderProduct->product_image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                                alt="{{ $orderProduct->product_name }}" width="50">
                                        </td>
                                        <td>
                                            @if ($product && $product->original_product?->url)
                                                <a href="{{ $product->original_product->url }}">{!! BaseHelper::clean($orderProduct->product_name) !!}</a>
                                            @else
                                                {!! BaseHelper::clean($orderProduct->product_name) !!}
                                            @endif
                                            @if ($sku = Arr::get($orderProduct->options, 'sku'))
                                                ({{ $sku }})
                                            @endif

                                            @if ($attributes = Arr::get($orderProduct->options, 'attributes'))
                                                <p class="mb-0">
                                                    <small>{{ $attributes }}</small>
                                                </p>
                                            @elseif ($product && $product->is_variation)
                                                <p>
                                                    <small>
                                                        @if ($attributes = get_product_attributes($product->getKey()))
                                                            @foreach ($attributes as $attribute)
                                                                {{ $attribute->attribute_set_title }}: {{ $attribute->title }}
                                                                @if (!$loop->last)
                                                                    ,
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </small>
                                                </p>
                                            @endif

                                            @include(
                                                EcommerceHelper::viewPath('includes.cart-item-options-extras'),
                                                ['options' => $orderProduct->options]
                                            )

                                            @if (!empty($orderProduct->product_options) && is_array($orderProduct->product_options))
                                                {!! render_product_options_html($orderProduct->product_options, $orderProduct->price) !!}
                                            @endif

                                            @if (is_plugin_active('marketplace') && ($product = $orderProduct->product) && $product->original_product->store->id)
                                                <p class="d-block mb-0 sold-by">
                                                    <small>{{ __('Sold by') }}: <a
                                                            href="{{ $product->original_product->store->url }}"
                                                            class="text-primary">{{ $product->original_product->store->name }}</a>
                                                    </small>
                                                </p>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $orderProduct->amount_format }}</td>
                                        <td class="text-center">{{ $orderProduct->qty }}</td>
                                        <td class="money text-end">
                                            <strong>
                                                {{ $orderProduct->total_format }}
                                            </strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if (EcommerceHelper::isTaxEnabled() && (float)$order->tax_amount)
                        <p>
                            <span class="d-inline-block me-1">{{ __('Tax') }}:</span>
                            <strong class="order-detail-value"> {{ format_price($order->tax_amount) }} </strong>
                        </p>
                    @endif

                    @if ((float)$order->discount_amount)
                        <p>
                            <span class="d-inline-block me-1">{{ __('Discount') }}:</span>
                            <strong class="order-detail-value"> {{ format_price($order->discount_amount) }}
                                @if ($order->discount_amount)
                                    @if ($order->coupon_code)
                                        ({!! BaseHelper::html(__('Coupon code: ":code"', ['code' => Html::tag('strong', $order->coupon_code)->toHtml()])) !!})
                                    @elseif ($order->discount_description)
                                        ({{ $order->discount_description }})
                                    @endif
                                @endif
                            </strong>
                        </p>
                    @endif

                    @if ((float)$order->shipping_amount && EcommerceHelper::countDigitalProducts($order->products) != $order->products->count())
                        <p>
                            <span class="d-inline-block me-1">{{ __('Shipping fee') }}: </span>
                            <strong>{{ format_price($order->shipping_amount) }}</strong>
                        </p>
                    @endif

                    <p>
                        <span class="d-inline-block me-1">{{ __('Total Amount') }}: </span>
                        <strong>{{ format_price($order->amount) }}</strong>
                    </p>
                </div>

                @if ($order->shipment->id)
                    <br>
                    <h5 class="mb-3">{{ __('Shipping Information') }}: </h5>
                    <p>
                        <span class="d-inline-block me-1">{{ __('Shipping Status') }}: </span>
                        <strong class="d-inline-block text-info">{!! BaseHelper::clean($order->shipment->status->toHtml()) !!}</strong>
                    </p>
                    @if ($order->shipment->shipping_company_name)
                        <p>
                            <span class="d-inline-block me-1">{{ __('Shipping Company Name') }}: </span>
                            <strong class="d-inline-block">{{ $order->shipment->shipping_company_name }}</strong>
                        </p>
                    @endif
                    @if ($order->shipment->tracking_id)
                        <p>
                            <span class="d-inline-block me-1">{{ __('Tracking ID') }}: </span>
                            <strong class="d-inline-block">{{ $order->shipment->tracking_id }}</strong>
                        </p>
                    @endif
                    @if ($order->shipment->tracking_link)
                        <p>
                            <span class="d-inline-block me-1">{{ __('Tracking Link') }}: </span>
                            <strong class="d-inline-block">
                                <a href="{{ $order->shipment->tracking_link }}"
                                    target="_blank">{{ $order->shipment->tracking_link }}</a>
                            </strong>
                        </p>
                    @endif
                    @if ($order->shipment->note)
                        <p>
                            <span class="d-inline-block me-1">{{ __('Delivery Notes') }}: </span>
                            <strong class="d-inline-block">{{ $order->shipment->note }}</strong>
                        </p>
                    @endif
                    @if ($order->shipment->estimate_date_shipped)
                        <p>
                            <span class="d-inline-block me-1">{{ __('Estimate Date Shipped') }}: </span>
                            <strong class="d-inline-block">{{ $order->shipment->estimate_date_shipped }}</strong>
                        </p>
                    @endif
                    @if ($order->shipment->date_shipped)
                        <p>
                            <span class="d-inline-block me-1">{{ __('Date Shipped') }}: </span>
                            <strong class="d-inline-block">{{ $order->shipment->date_shipped }}</strong>
                        </p>
                    @endif
                @endif
            </div>
        </div>
    </div>
@elseif (request()->input('order_id') || request()->input('email'))
    <div role="alert" class="alert alert-danger mt-3">
        <div class="d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-triangle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M12 9v4"></path>
                <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"></path>
                <path d="M12 16h.01"></path>
            </svg>

            {{ __('The order could not be found. Please try again or contact us if you need assistance.') }}
        </div>
    </div>
@endif
