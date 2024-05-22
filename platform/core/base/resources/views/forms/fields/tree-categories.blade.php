@if (
    isset($options['choices'])
    && (is_array($options['choices']) || $options['choices'] instanceof \Illuminate\Support\Collection)
)
    @if(count($options['choices']) < 20)
        <div data-bb-toggle="tree-checkboxes">
            @include('core/base::forms.partials.tree-categories-checkbox-options', [
                'categories' => $options['choices'],
                'selected' => $options['selected'],
                'currentId' => null,
                'name' => $name,
            ])
        </div>
    @else
        <x-core::form.select
            :multiple="true"
            :name="$name"
            data-bb-toggle="tree-categories-select"
        >
            @include('core/base::forms.partials.tree-categories-select-options', [
                'categories' => $options['choices'],
                'selected' => $options['selected'],
                'currentId' => null,
                'name' => $name,
                'indent' => null,
            ])
        </x-core::form.select>
    @endif
@endif
