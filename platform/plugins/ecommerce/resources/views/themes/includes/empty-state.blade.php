@php
    $title = $title ?? __('Your cart is empty');
    $description = $description ?? __('Explore and add items to get started');
    $route = $route ?? route('public.products');
    $label = $label ?? __('Start Shopping');
@endphp

<div class="text-center pt-50 bb-empty-state">
    <h3 class="mb-3">{!! BaseHelper::clean($title) !!}</h3>
    <p class="mb-3">{!! BaseHelper::clean($description) !!}</p>
    <a href="{{ $route }}" class="btn btn-outline-primary">{!! BaseHelper::clean($label) !!}</a>
</div>
