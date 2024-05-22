<select
    {{ $attributes->merge(['name' => 'categories[]']) }}
    data-bb-toggle="init-categories-dropdown"
    data-url="{{ route('public.ajax.categories-dropdown') }}"
>
    <option value="">{{ __('All Categories') }}</option>
</select>
