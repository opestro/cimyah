@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', SeoHelper::getTitle())

@section('content')
    @include(EcommerceHelper::viewPath('customers.product-reviews.icons'))

    <div class="product-reviews-page">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link @if (!request()->has('page')) active @endif"
                    id="waiting-tab"
                    data-toggle="tab"
                    data-target="#waiting-tab-pane"
                    data-bs-toggle="tab"
                    data-bs-target="#waiting-tab-pane"
                    type="button"
                    role="tab"
                    aria-controls="waiting-tab-pane"
                    aria-selected="true"
                >
                    {{ __('Waiting for your review') }} ({{ $products->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link @if (request()->has('page')) active @endif"
                    id="reviewed-tab"
                    data-toggle="tab"
                    data-target="#reviewed-tab-pane"
                    data-bs-toggle="tab"
                    data-bs-target="#reviewed-tab-pane"
                    type="button"
                    role="tab"
                    aria-controls="reviewed-tab-pane"
                    aria-selected="false"
                >
                    {{ __('Reviewed') }} ({{ $reviews->total() }})
                </button>
            </li>
        </ul>

        <div class="tab-content pt-3">
            <div class="tab-pane fade @if (!request()->has('page')) show active @endif" id="waiting-tab-pane" role="tabpanel" aria-labelledby="waiting-tab" tabindex="0">
                @if ($products->isNotEmpty())
                    <div class="row row-cols-md-2 row-cols-1 g-3">
                        @foreach ($products as $product)
                            <div class="col">
                                <div class="ecommerce-product-item border p-3" data-id="{{ $product->id }}">
                                    <div class="d-flex gap-2">
                                        {{ RvMedia::image($product->order_product_image ?: $product->image, $product->name, 'thumb', true, ['class' => 'img-fluid rounded-start ecommerce-product-image']) }}

                                        <div>
                                            <a href="{{ $product->url }}">
                                                <h6 class="card-title ecommerce-product-name">
                                                    {!! BaseHelper::clean($product->order_product_name ?: $product->name) !!}
                                                </h6>
                                            </a>

                                            @if ($product->order_completed_at)
                                                <div class="text-muted mt-1">
                                                    {{ __('Order completed at') }}:
                                                    <time>{{ Carbon\Carbon::parse($product->order_completed_at)->translatedFormat('M d, Y h:m') }}</time>
                                                </div>
                                            @endif

                                            <div class="d-flex ecommerce-product-star mt-1 w-50">
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <label class="order-{{ $i }}">
                                                        <x-core::icon name="ti ti-star-filled" class="ecommerce-icon" data-star="{{ $i }}" />
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div role="alert" class="alert alert-info">{{ __('You do not have any products to review yet. Just shopping!') }}</div>
                @endif
            </div>

            <div class="tab-pane fade @if (request()->has('page')) show active @endif" id="reviewed-tab-pane" role="tabpanel" aria-labelledby="reviewed-tab" tabindex="0">
                @include(EcommerceHelper::viewPath('customers.product-reviews.reviewed'))
            </div>
        </div>

        @include(EcommerceHelper::viewPath('customers.product-reviews.modal'))
    </div>
@endsection
