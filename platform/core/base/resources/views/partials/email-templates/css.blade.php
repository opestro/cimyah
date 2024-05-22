<style>
    {!! BaseHelper::clean(File::get(core_path('base/resources/email-templates/default.css'))) !!}

    @if ($customCSS = setting('email_template_custom_css'))
        {!! BaseHelper::clean($customCSS) !!}
    @endif

    {!! BaseHelper::clean((string) apply_filters('email_template_css', null)) !!}
</style>
