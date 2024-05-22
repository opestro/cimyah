<div class="tp-widget-product-categories">
    @if ($config['name'])
        <h5 class="tp-widget-product-categories-title">
            {{ $config['name'] }}:
        </h5>
    @endif

    <div class="tp-widget-product-categories-list">
        @foreach ($categories as $category)
            <a href="{{ $category->url }}" title="{{ $category->name }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
</div>
