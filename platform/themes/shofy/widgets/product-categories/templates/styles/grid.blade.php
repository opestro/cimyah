<section class="tp-category-area py-20 pb-20">
    <div class="tp-featured-category row g-4">
        @foreach($categories as $category)
            <div class="tp-featured-category-item col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ $category->url }}" class="tp-featured-category-image">
                    {{ RvMedia::image($category->image, $category->name, attributes: ['loading' => false]) }}
                </a>
                <div @class(['tp-featured-category-content d-flex flex-column pt-3 px-2', 'text-center' => $config['centered_text']])>
                    <h5 class="fs-6 fw-medium">
                        <a href="{{ $category->url }}">{{ $category->name }}</a>
                    </h5>

                    @if($config['display_children'] && $category->activeChildren)
                        <ul class="list-unstyled">
                            @foreach($category->activeChildren as $childCategory)
                                @if($loop->index < 3)
                                    <li>
                                        <a href="{{ $childCategory->url }}">{{ $childCategory->name }}</a>
                                    </li>
                                @else
                                    @if($loop->last)
                                        <li>
                                            <a href="{{ $category->url }}">{{ __('More...') }}</a>
                                        </li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>
