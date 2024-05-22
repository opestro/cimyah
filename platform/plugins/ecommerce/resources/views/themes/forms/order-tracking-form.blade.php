@if ($description = Arr::get($formOptions, 'description'))
    <div class="text-center mb-5">
        <p>{{ $description }}</p>
    </div>
@endif

@include('core/base::forms.form-content-only')
