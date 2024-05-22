<div class="tp-product-quantity mt-10 mb-10">
    <span class="tp-cart-minus" data-bb-toggle="decrease-qty">
        <svg width="10" height="2" viewBox="0 0 10 2" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 1H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </span>
    <input
        class="tp-cart-input"
        type="number"
        name="items[{{ $key }}][values][qty]"
        value="{{ $cartItem->qty }}"
        min="1"
        max="{{ $product->with_storehouse_management ? $product->quantity : 1000 }}"
        data-bb-toggle="update-cart"
    />
    <span class="tp-cart-plus" data-bb-toggle="increase-qty">
        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5 1V9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M1 5H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </span>
</div>
