<div class="bb-filter-offcanvas-area">
    <div class="bb-filter-offcanvas-wrapper">
        <div class="bb-filter-offcanvas-close">
            <button type="button" class="bb-filter-offcanvas-close-btn" data-bb-toggle="toggle-filter-sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18 6l-12 12" />
                    <path d="M6 6l12 12" />
                </svg>
                {{ __('Close') }}
            </button>
        </div>

        <div class="bb-shop-sidebar">
            <form action="{{ URL::current() }}" method="GET" class="bb-product-form-filter">
                <input type="hidden" name="sort-by" value="{{ BaseHelper::stringify(request()->query('sort-by')) }}">
                <input type="hidden" name="per-page" value="{{ BaseHelper::stringify(request()->query('per-page')) }}">
                <input type="hidden" name="layout" value="{{ BaseHelper::stringify(request()->query('layout')) }}">
                <input type="hidden" name="page" value="{{ BaseHelper::stringify(request()->query('page')) }}">
                <input name="categories[]" type="hidden" value="{{ $categoryId }}">

                @include(EcommerceHelper::viewPath('includes.filters.search'))
                @include(EcommerceHelper::viewPath('includes.filters.categories'))
            </form>
        </div>
    </div>
</div>
