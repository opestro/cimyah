@php
    Theme::asset()->add('faqs-css', 'vendor/core/plugins/ecommerce/css/front-faq.css', version: get_cms_version());
@endphp

<div class="product-faqs-accordion accordion" id="faqs-accordion">
    @foreach ($faqs as $faq)
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button
                    @class(['accordion-button', 'collapsed' => ! $loop->first])
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapse-{{ $loop->index }}" aria-expanded="true"
                    aria-controls="collapse-{{ $loop->index }}"
                >
                    {!! BaseHelper::clean($faq[0]['value']) !!}
                </button>
            </h2>
            <div id="collapse-{{ $loop->index }}" @class(['accordion-collapse collapse', 'show' => $loop->first]) data-bs-parent="#faqs-accordion">
                <div class="accordion-body">
                    {!! BaseHelper::clean($faq[1]['value']) !!}
                </div>
            </div>
        </div>
    @endforeach
</div>
