@if ($clientId = setting('ads_google_adsense_unit_client_id'))
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="{{ $clientId }}"
         data-ad-slot="{{ $slotId }}"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
@endif
