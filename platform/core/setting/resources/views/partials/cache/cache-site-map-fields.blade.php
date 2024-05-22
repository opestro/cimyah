<x-core::form.on-off.checkbox
    name="enable_cache_site_map"
    :label="trans('core/setting::setting.cache.form.enable_cache_site_map')"
    :checked="setting('enable_cache_site_map', true)"
    data-bb-toggle="collapse"
    data-bb-target=".cache-sitemap"
    :wrapper="false"
/>

<x-core::form.fieldset
    data-bb-value="1"
    class="cache-sitemap mt-3"
    @style(['display: none;' => !setting('enable_cache_site_map', true)])
>
    <x-core::form.text-input
        name="cache_time_site_map"
        type="number"
        :label="trans('core/setting::setting.cache.form.cache_time_site_map')"
        :value="setting('cache_time_site_map', 60)"
    />
</x-core::form.fieldset>
