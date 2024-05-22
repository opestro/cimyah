<div class="row row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3">
    @foreach($products as $product)
        <div class="col">
            @include(EcommerceHelper::viewPath('includes.product-item'))
        </div>
    @endforeach
</div>

@include(EcommerceHelper::viewPath('includes.quick-shop-modal'))
@include(EcommerceHelper::viewPath('includes.quick-view-modal'))
