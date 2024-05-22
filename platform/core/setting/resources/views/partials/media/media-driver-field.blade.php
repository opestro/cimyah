<x-core-setting::form-group>
    <x-core::form.select
        name="media_driver"
        :label="trans('core/setting::setting.media.driver')"
        :options="[
            'public' => 'Local disk',
            's3' => 'Amazon S3',
            'r2' => 'Cloudflare R2',
            'do_spaces' => 'DigitalOcean Spaces',
            'wasabi' => 'Wasabi',
            'bunnycdn' => 'BunnyCDN',
        ]"
        :value="RvMedia::getMediaDriver()"
        data-bb-toggle="collapse"
        data-bb-target=".media-driver"
    />

    <x-core::form.fieldset
        data-bb-value="s3"
        class="media-driver"
        @style(['display: none;' => old('media_driver', RvMedia::getMediaDriver()) !== 's3'])
    >
        <x-core::form.text-input
            name="media_aws_access_key_id"
            :label="trans('core/setting::setting.media.aws_access_key_id')"
            :value="config('filesystems.disks.s3.key')"
            placeholder="Ex: AKIAIKYXBSNBXXXXXX"
        />

        <x-core::form.text-input
            name="media_aws_secret_key"
            :label="trans('core/setting::setting.media.aws_secret_key')"
            :value="config('filesystems.disks.s3.secret')"
            placeholder="Ex: +fivlGCeTJCVVnzpM2WfzzrFIMLHGhxxxxxxx"
        />

        <x-core::form.text-input
            name="media_aws_default_region"
            :label="trans('core/setting::setting.media.aws_default_region')"
            :value="config('filesystems.disks.s3.region')"
            placeholder="Ex: ap-southeast-1"
        />

        <x-core::form.text-input
            name="media_aws_bucket"
            :label="trans('core/setting::setting.media.aws_bucket')"
            :value="config('filesystems.disks.s3.bucket')"
            placeholder="Ex: botble"
        />

        <x-core::form.text-input
            name="media_aws_url"
            :label="trans('core/setting::setting.media.aws_url')"
            :value="config('filesystems.disks.s3.url')"
            placeholder="Ex: https://s3-ap-southeast-1.amazonaws.com/botble"
        />

        <x-core::form.text-input
            name="media_aws_endpoint"
            :label="trans('core/setting::setting.media.aws_endpoint')"
            :value="config('filesystems.disks.s3.endpoint')"
            :placeholder="trans('core/setting::setting.media.optional')"
        />
    </x-core::form.fieldset>

    <x-core::form.fieldset
        data-bb-value="r2"
        class="media-driver"
        @style(['display: none;' => old('media_driver', RvMedia::getMediaDriver()) !== 'r2'])
    >
        <x-core::form.text-input
            name="media_r2_access_key_id"
            :label="trans('core/setting::setting.media.r2_access_key_id')"
            :value="config('filesystems.disks.r2.key')"
            placeholder="Ex: AKIAIKYXBSNBXXXXXX"
        />

        <x-core::form.text-input
            name="media_r2_secret_key"
            :label="trans('core/setting::setting.media.r2_secret_key')"
            :value="config('filesystems.disks.r2.secret')"
            placeholder="Ex: +fivlGCeTJCVVnzpM2WfzzrFIMLHGhxxxxxxx"
        />

        <x-core::form.text-input
            name="media_r2_bucket"
            :label="trans('core/setting::setting.media.r2_bucket')"
            :value="config('filesystems.disks.r2.bucket')"
            placeholder="Ex: botble"
        />

        <x-core::form.text-input
            name="media_r2_endpoint"
            :label="trans('core/setting::setting.media.r2_endpoint')"
            :value="config('filesystems.disks.r2.endpoint')"
            placeholder="Ex: https://xxx.r2.cloudflarestorage.com"
        />

        <x-core::form.text-input
            name="media_r2_url"
            :label="trans('core/setting::setting.media.r2_url')"
            :value="config('filesystems.disks.r2.url')"
            placeholder="Ex: https://pub-f70218cc331a40689xxx.r2.dev"
        />
    </x-core::form.fieldset>

    <x-core::form.fieldset
        data-bb-value="do_spaces"
        class="media-driver"
        @style(['display: none;' => old('media_driver', RvMedia::getMediaDriver()) !== 'do_spaces'])
    >
        <x-core::form.text-input
            name="media_do_spaces_access_key_id"
            :label="trans('core/setting::setting.media.do_spaces_access_key_id')"
            :value="config('filesystems.disks.do_spaces.key')"
            placeholder="Ex: AKIAIKYXBSNBXXXXXX"
        />

        <x-core::form.text-input
            name="media_do_spaces_secret_key"
            :label="trans('core/setting::setting.media.do_spaces_secret_key')"
            :value="config('filesystems.disks.do_spaces.secret')"
            placeholder="Ex: +fivlGCeTJCVVnzpM2WfzzrFIMLHGhxxxxxxx"
        />

        <x-core::form.text-input
            name="media_do_spaces_default_region"
            :label="trans('core/setting::setting.media.do_spaces_default_region')"
            :value="config('filesystems.disks.do_spaces.region')"
            placeholder="Ex: SGP1"
        />

        <x-core::form.text-input
            name="media_do_spaces_bucket"
            :label="trans('core/setting::setting.media.do_spaces_bucket')"
            :value="config('filesystems.disks.do_spaces.bucket')"
            placeholder="Ex: botble"
        />

        <x-core::form.text-input
            name="media_do_spaces_endpoint"
            :label="trans('core/setting::setting.media.do_spaces_endpoint')"
            :value="config('filesystems.disks.do_spaces.endpoint')"
            placeholder="Ex: https://botble.sfo2.digitaloceanspaces.com"
        />

        <x-core::form.on-off.checkbox
            :label="trans('core/setting::setting.media.do_spaces_cdn_enabled')"
            name="media_do_spaces_cdn_enabled"
            :checked="setting('media_do_spaces_cdn_enabled')"
        />

        <x-core::form.text-input
            name="media_do_spaces_cdn_custom_domain"
            :label="trans('core/setting::setting.media.media_do_spaces_cdn_custom_domain')"
            :value="setting('media_do_spaces_cdn_custom_domain')"
            :placeholder="trans('core/setting::setting.media.media_do_spaces_cdn_custom_domain_placeholder')"
        />
    </x-core::form.fieldset>

    <x-core::form.fieldset
        data-bb-value="wasabi"
        class="media-driver"
        @style(['display: none;' => old('media_driver', RvMedia::getMediaDriver()) !== 'wasabi'])
    >
        <x-core::form.text-input
            name="media_wasabi_access_key_id"
            :label="trans('core/setting::setting.media.wasabi_access_key_id')"
            :value="config('filesystems.disks.wasabi.key')"
            placeholder="Ex: AKIAIKYXBSNBXXXXXX"
        />

        <x-core::form.text-input
            name="media_wasabi_secret_key"
            :label="trans('core/setting::setting.media.wasabi_secret_key')"
            :value="config('filesystems.disks.wasabi.secret')"
            placeholder="Ex: +fivlGCeTJCVVnzpM2WfzzrFIMLHGhxxxxxxx"
        />

        <x-core::form.text-input
            name="media_wasabi_default_region"
            :label="trans('core/setting::setting.media.wasabi_default_region')"
            :value="config('filesystems.disks.wasabi.region')"
            placeholder="Ex: us-east-1"
        />

        <x-core::form.text-input
            name="media_wasabi_bucket"
            :label="trans('core/setting::setting.media.wasabi_bucket')"
            :value="config('filesystems.disks.wasabi.bucket')"
            placeholder="Ex: botble"
        />

        <x-core::form.text-input
            name="media_wasabi_root"
            :label="trans('core/setting::setting.media.wasabi_root')"
            :value="config('filesystems.disks.wasabi.root')"
            placeholder="Default: /"
            :helperText="trans('core/setting::setting.media.wasabi_root_helper')"
        />
    </x-core::form.fieldset>

    <x-core::form.fieldset
        data-bb-value="bunnycdn"
        class="media-driver"
        @style(['display: none;' => old('media_driver', RvMedia::getMediaDriver()) !== 'bunnycdn'])
    >
        <x-core::form.text-input
            name="media_bunnycdn_hostname"
            :label="trans('core/setting::setting.media.bunnycdn_hostname')"
            :value="setting('media_bunnycdn_hostname')"
            placeholder="Ex: botble.b-cdn.net"
        />

        <x-core::form.text-input
            name="media_bunnycdn_zone"
            :label="trans('core/setting::setting.media.bunnycdn_zone')"
            :value="setting('media_bunnycdn_zone')"
            placeholder="Ex: botble"
        />

        <x-core::form.text-input
            name="media_bunnycdn_key"
            :label="trans('core/setting::setting.media.bunnycdn_key')"
            :value="setting('media_bunnycdn_key')"
            placeholder="Ex: 9a734df7-844b-..."
        />

        <x-core::form.select
            name="media_bunnycdn_region"
            :label="trans('core/setting::setting.media.bunnycdn_region')"
            :options="[
                '' => 'Falkenstein',
                'ny' => 'New York',
                'la' => 'Los Angeles',
                'sg' => 'Singapore',
                'syd' => 'Sydney',
            ]"
            :value="setting('media_bunnycdn_region')"
        />
    </x-core::form.fieldset>
</x-core-setting::form-group>
