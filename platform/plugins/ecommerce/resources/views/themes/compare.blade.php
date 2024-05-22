<section class="compare-area pt-50 pb-50">
    <div class="container">
        @if ($products->isNotEmpty())
            <div class="compare-table table-responsive text-center">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th>{{ __('Product') }}</th>
                        @foreach ($products as $product)
                            <td>
                                <div class="compare-thumb">
                                    {{ RvMedia::image($product->image, $product->name, 'thumb') }}
                                    <h4 class="compare-product-title">
                                        <a href="{{ $product->url }}">{{ $product->name }}</a>
                                    </h4>

                                    <span @class(['text-danger' => $product->isOutOfStock(), 'text-success' => ! $product->isOutOfStock()])>
                                        @if ($product->isOutOfStock())
                                            ({{ __('Out of stock') }})
                                        @else
                                            ({{ __('In stock') }})
                                        @endif
                                    </span>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>{{ __('Description') }}</th>
                        @foreach ($products as $product)
                            <td>
                                <div class="compare-desc">
                                    {!! BaseHelper::clean($product->description) !!}
                                </div>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>{{ __('Price') }}</th>
                        @foreach ($products as $product)
                            <td>
                                @include(EcommerceHelper::viewPath('includes.product-price'), [
                                    'priceWrapperClassName' => 'compare-price',
                                    'priceClassName' => '',
                                    'priceOriginalWrapperClassName' => '',
                                    'priceOriginalClassName' => 'old-price',
                                ])
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>{{ __('SKU') }}</th>
                        @foreach ($products as $product)
                            <td>{{ $product->sku ? '#' . $product->sku : '' }}</td>
                        @endforeach
                    </tr>
                    @foreach ($attributeSets as $attributeSet)
                        @continue(! $attributeSet->is_comparable)

                        <tr>
                            <th>{{ $attributeSet->title }}</th>

                            @foreach ($products as $product)
                                <td>
                                    {{ render_product_attributes_view_only($product, $attributeSet) }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    <tr>
                        <th>{{ __('Add to cart') }}</th>
                        @foreach ($products as $product)
                            <td>
                                <div class="compare-add-to-cart d-flex justify-content-center">
                                    <button
                                        title="{{ __('Add to Cart') }}"
                                        type="submit"
                                        class="btn btn-primary bb-btn-product-actions-icon"
                                        data-bb-toggle="add-to-cart"
                                        data-url="{{ route('public.cart.add-to-cart') }}"
                                        data-id="{{ $product->original_product->id }}"
                                        {!! EcommerceHelper::jsAttributes('add-to-cart', $product) !!}
                                    >
                                        <x-core::icon name="ti ti-shopping-cart"/>
                                        {{ __('Add to Cart') }}
                                    </button>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>{{ __('Rating') }}</th>
                        @foreach ($products as $product)
                            <td>
                                <div class="compare-rating d-flex justify-content-center">
                                    @include(EcommerceHelper::viewPath('includes.rating-star'), ['avg' => $product->reviews_avg, 'size' => 80])
                                </div>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>{{ __('Remove') }}</th>
                        @foreach ($products as $product)
                            <td>
                                <div class="compare-remove">
                                    <button class="btn btn-icon" data-bb-toggle="remove-from-compare" data-url="{{ route('public.compare.remove', $product->id) }}">
                                        <x-core::icon name="ti ti-trash" />
                                    </button>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>
        @else
            @include(EcommerceHelper::viewPath('includes.empty-state'), ['title' => __('Your compare list is empty')])
        @endif
    </div>
</section>
