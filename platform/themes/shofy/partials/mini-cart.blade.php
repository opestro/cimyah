<div class="cartmini__wrapper d-flex justify-content-between flex-column">
    <div class="cartmini__top-wrapper">
        <div class="cartmini__top p-relative">
            <div class="cartmini__top-title">
                <h4>{{ __('Shopping cart') }}</h4>
            </div>
            <div class="cartmini__close" title="Close">
                <button type="button" class="cartmini__close-btn cartmini-close-btn" title="Close">
                    <x-core::icon name="ti ti-x" />
                </button>
            </div>
        </div>

        @if ($ajax ?? false)
            {!! Theme::partial('mini-cart.content') !!}
        @else
            <div data-bb-toggle="mini-cart-content-slot"></div>
        @endif
    </div>

    @if ($ajax ?? false)
        {!! Theme::partial('mini-cart.footer') !!}
    @else
        <div data-bb-toggle="mini-cart-footer-slot"></div>
    @endif
</div>
