<dd>{{ $tax->company_name }}</dd>

<dd>{{ $tax->company_tax_code }}</dd>

<dd>
    <a href="mailto:{{ $tax->company_email }}">
        <span>
            <i class="cursor-pointer mr5"></i>
            <x-core::icon name="ti ti-mail" class="cursor-pointer mr5" />
        </span>
        <span dir="ltr">{{ $tax->company_email }}</span>
    </a>
</dd>

<dd>
    <div>{{ $tax->company_address }}</div>

    <div>
        <a
            class="hover-underline"
            href="https://maps.google.com/?q={{ $tax->company_address }}"
            target="_blank"
        >{{ trans('plugins/ecommerce::order.see_on_maps') }}</a>
    </div>
</dd>

