<form {{ $attributes->merge([
    'role' => 'search',
    'action' => route('public.products'),
    'data-ajax-url' => route('public.ajax.search-products'),
    'method' => 'GET',
    'class' => 'bb-form-quick-search',
    'id' => 'bb-form-quick-search',
]) }}>
    {{ $slot }}

    <div class="bb-quick-search-results"></div>
</form>
