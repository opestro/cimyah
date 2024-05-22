<fieldset class="form-fieldset">
    <h3>{{ trans('plugins/ads::ads.settings.google_adsense_unit_ads_where_to_get_client_id') }}</h3>

    <p>{!! BaseHelper::clean(trans('plugins/ads::ads.settings.google_adsense_unit_ads_guide_to_get_client_id')) !!}</p>

    <p>{{ trans('plugins/ads::ads.settings.google_adsense_unit_ads_guide_example_snippet') }}</p>

<pre><code>&lt;script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-123456789"
    crossorigin="anonymous"&gt;&lt;/script&gt;
&lt;ins class="adsbygoogle"
    style="display:block"
    data-ad-client="ca-pub-123456789
    data-ad-slot="123456789"
    data-ad-format="auto"&gt;&lt;/ins&gt;
&lt;script&gt;
    (adsbygoogle = window.adsbygoogle || []).push({});
&lt;/script&gt;</code></pre>
</fieldset>
