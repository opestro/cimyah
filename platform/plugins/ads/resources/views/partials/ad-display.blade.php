@foreach($data as $item)
    @if ($item->ads_type === 'google_adsense' && $item->google_adsense_slot_id)
        <div {!! Html::attributes($attributes) !!}>
            @include('plugins/ads::partials.google-adsense.unit-ads-slot', ['slotId' => $item->google_adsense_slot_id])
        </div>
        @continue
    @endif

    @continue(! $item->image)

    <div {!! Html::attributes($attributes) !!}>
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

                    {{ RvMedia::image($item->image_url, $item->name, attributes: ['style' => 'max-width: 100%']) }}
                </picture>
        @if($item->url)
            </a>
        @endif
    </div>
@endforeach
