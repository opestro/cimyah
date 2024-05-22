@if (File::exists(public_path('ads.txt')))
    <x-core::button
        type="submit"
        color="danger"
        size="md"
        name="google_adsense_ads_delete_txt"
    >
        <x-core::icon name="ti ti-trash" /> {{ __('Delete ads.txt file') }}
    </x-core::button>

    <small class="form-hint mt-2">
        {!! BaseHelper::clean(__('View your ads.txt here: :url', ['url' => Html::link(url('ads.txt'), attributes: ['target' => '_blank'])])) !!}
    </small>
@endif
