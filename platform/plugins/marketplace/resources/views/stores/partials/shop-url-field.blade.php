<div class="row">
    <div class="col-12">
        <input
            name="reference_id"
            type="hidden"
            value="{{ $store->id }}"
        >
        <input type="hidden" name="is_slug_editable" value="1">

        <x-core::form.text-input
            wrapper-class="shop-url-wrapper"
            :label="__('Shop URL')"
            label-description=" "
            type="text"
            id="shop-url"
            name="slug"
            :data-url="route('public.ajax.check-store-url')"
            :value="old('slug', $store->slug)"
            :placeholder="__('Shop URL')"
            :required="true"
        >
            <x-core::form.helper-text data-base-url="{{ route('public.store', old('slug', '')) }}">
                {{ route('public.store', old('slug', $store->slug)) }}
            </x-core::form.helper-text>
        </x-core::form.text-input>
    </div>
</div>
