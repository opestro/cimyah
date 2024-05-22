@php
    Theme::layout('full-width');
@endphp

<section class="tp-order-area pb-160 pt-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="tp-order-inner">
                    <div class="tp-order-info-wrapper">
                        {!! $form->renderForm() !!}
                    </div>
                </div>

                @include(EcommerceHelper::viewPath('includes.order-tracking-detail'))
            </div>
        </div>
    </div>
</section>
