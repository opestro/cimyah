@php
    $values = Arr::wrap($values ?? []);
@endphp

<div class="position-relative form-check-group mb-3">
    @foreach ($values as $key => $option)
        <x-core::form.radio
            :name="$name"
            :value="$key"
            :checked="$key == $selected"
            :attributes="new Illuminate\View\ComponentAttributeBag((array) $attributes)"
        >
            {{ $option }}
        </x-core::form.radio>
    @endforeach
</div>

