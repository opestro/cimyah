<x-core::form.on-off.checkbox
    name="enable_cache"
    :label="trans('core/setting::setting.cache.form.enable_cache')"
    :checked="setting('enable_cache', false)"
    data-bb-toggle="collapse"
    data-bb-target=".cache-time"
/>

<x-core::form.fieldset
    class="cache-time"
    data-bb-value="1"
    @style(['display: none;' => !setting('enable_cache', false)])
>
    <x-core::form.text-input
        name="cache_time"
        :label="trans('core/setting::setting.cache.form.cache_time')"
        type="number"
        :value="setting('cache_time', 10)"
        data-counter="120"
    />

    <x-core::form.on-off.checkbox
        name="disable_cache_in_the_admin_panel"
        :label="trans('core/setting::setting.cache.form.disable_cache_in_the_admin_panel')"
        :checked="setting('disable_cache_in_the_admin_panel', true)"
    />
</x-core::form.fieldset>
