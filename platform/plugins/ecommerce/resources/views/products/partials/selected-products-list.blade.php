@foreach ($products as $relatedProduct)
    <div class="list-group-item">
        <div class="row align-items-center">
            <div class="col-auto">
                <span
                    class="avatar"
                    style="background-image: url('{{ RvMedia::getImageUrl($relatedProduct->image, 'thumb', false, RvMedia::getDefaultImage()) }}')"
                ></span>
            </div>
            <div class="col text-truncate">
                <a href="{{ route('products.edit', $relatedProduct->id) }}" class="text-body d-block" target="_blank">{{ $relatedProduct->name }}</a>
                @if ($includeVariation && $relatedProduct->variationInfo->id)
                    <div class="text-secondary text-truncate">
                        @foreach ($relatedProduct->variationInfo->variationItems as $variationItem)
                            <span>
                                {{ $variationItem->attribute->title }}
                                @if (!$loop->last)
                                    <span> / </span>
                                @endif
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-auto">
                <a
                    href="javascript:void(0)"
                    class="text-decoration-none list-group-item-actions"
                    data-bb-toggle="product-delete-item"
                    data-bb-target="{{ $relatedProduct->id }}"
                    title="{{ trans('plugins/ecommerce::products.delete') }}"
                >
                    <x-core::icon name="ti ti-x" class="text-secondary" />
                </a>
            </div>
        </div>
    </div>
@endforeach
