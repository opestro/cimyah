<x-core::form.on-off.checkbox
    name="blog_post_schema_enabled"
    :label="trans('plugins/blog::base.settings.enable_blog_post_schema')"
    :checked="setting('blog_post_schema_enabled', true)"
    :description="trans('plugins/blog::base.settings.enable_blog_post_schema_description')"
    data-bb-toggle="collapse"
    data-bb-target=".blog_post_schema_type"
    class="mb-0"
    :wrapper="false"
/>

<x-core::form.fieldset
    class="blog_post_schema_type mt-3"
    data-bb-value="1"
    @style(['display: none' => !setting('blog_post_schema_enabled', true)])
>
    <x-core::form.select
        name="blog_post_schema_type"
        :label="trans('plugins/blog::base.settings.schema_type')"
        :options="[
            'NewsArticle' => 'NewsArticle',
            'News' => 'News',
            'Article' => 'Article',
            'BlogPosting' => 'BlogPosting',
        ]"
        :value="setting('blog_post_schema_type', 'NewsArticle')"
    />
</x-core::form.fieldset>
