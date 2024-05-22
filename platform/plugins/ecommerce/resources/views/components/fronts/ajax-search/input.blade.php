<input {{ $attributes->merge([
    'type' => 'search',
    'name' => 'q',
    'placeholder' => __('Search for Products...'),
    'value' => BaseHelper::stringify(request()->query('q')),
    'autocomplete' => 'off',
]) }}>
