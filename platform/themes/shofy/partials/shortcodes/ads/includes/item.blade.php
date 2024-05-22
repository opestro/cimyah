@if ($item->url)
    <a href="{{ $item->click_url }}" @if($item->open_in_new_tab) target="_blank" @endif>
@endif
        <picture>
            <source
                srcset="{{ $item->image_url }}"
                media="(min-width: 1200px)"
            />
            <source
                srcset="{{ $item->tablet_image_url }}"
                media="(min-width: 768px)"
            />
            <source
                srcset="{{ $item->mobile_image_url }}"
                media="(max-width: 767px)"
            />

            {{ RvMedia::image($item->image_url, $item->name, attributes: ['style' => 'width: 100%']) }}
        </picture>
@if($item->url)
    </a>
@endif
