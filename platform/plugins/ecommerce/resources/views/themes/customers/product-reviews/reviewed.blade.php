<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>{{ __('Image') }}</th>
                <th>{{ __('Product Name') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Star') }}</th>
                <th>{{ __('Comment') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @if ($reviews->total() > 0)
                @foreach ($reviews as $item)
                    <tr>
                        <th scope="row">
                            <img
                                class="img-thumb"
                                src="{{ RvMedia::getImageUrl($item->product->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                alt="{{ $item->product->name }}"
                                style="max-width: 70px"
                            >
                        </th>
                        <th scope="row">
                            <a href="{{ $item->product->url }}">{!! BaseHelper::clean($item->product->name) !!}</a>

                            @if ($sku = $item->product->sku)
                                <p><small>({{ $sku }})</small></p>
                            @endif

                            @if (is_plugin_active('marketplace') && $item->product->store->id)
                                <p class="d-block mb-0 sold-by">
                                    <small>{{ __('Sold by') }}: <a href="{{ $item->product->original_product->store->url }}" class="text-primary">{{ $item->product->store->name }}</a>
                                    </small>
                                </p>
                            @endif
                        </th>
                        <td>{{ $item->created_at->translatedFormat('M d, Y h:m') }}</td>
                        <td>
                            <span>{{ $item->star }}</span>
                            <x-core::icon name="ti ti-star" class="ecommerce-icon text-warning" />
                        </td>
                        <td><span title="{{ $item->comment }}">{{ Str::limit($item->comment, 120) }}</span></td>
                        <td>
                            {!! Form::open([
                                'url' => route('public.reviews.destroy', $item->id),
                                'onSubmit' => 'return confirm("' . __('Do you really want to delete the review?') . '")',
                            ]) !!}
                                <input
                                    name="_method"
                                    type="hidden"
                                    value="DELETE"
                                >
                                <button class="btn btn-danger btn-sm">{{ __('Delete') }}</button>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="6">{{ __('No reviews!') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<div class="pagination">
    {!! $reviews->links() !!}
</div>
