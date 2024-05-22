@foreach ($attributeSets as $attributeSet)
    @php
        $selected = Arr::get($selectedAttrs, $attributeSet->slug, $selectedAttrs);
        $view = EcommerceHelper::viewPath("attributes._layouts-filter.$attributeSet->display_layout");

        if (! view()->exists($view)) {
            $view = EcommerceHelper::viewPath('attributes._layouts.dropdown');
        }
    @endphp

    @include($view, [
        'set' => $attributeSet,
        'attributes' => $attributeSet->attributes,
    ])
@endforeach
