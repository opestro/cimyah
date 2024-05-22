@php
    $description = Arr::get($config, 'description');
    $image = Arr::get($config, 'image');
@endphp

@if($messages)
    <div class="tp-product-details-msg mb-15">
        <ul>
            @foreach($messages as $message)
                <li>{!! BaseHelper::clean($message) !!}</li>
            @endforeach
        </ul>
    </div>
@endif

@if($description || $image)
    <div class="tp-product-details-payment d-flex align-items-center flex-wrap justify-content-between gap-3">
        @if($description)
            <p>{!! BaseHelper::clean($description) !!}</p>
        @endif

        @if($image)
            {{ RvMedia::image($image, $description) }}
        @endif
    </div>
@endif
