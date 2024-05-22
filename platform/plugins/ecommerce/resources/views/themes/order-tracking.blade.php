<section class="order-tracking">
    <div class="row justify-content-center">
        <div class="col-md-6">
            {!! $form->renderForm() !!}

            @include(EcommerceHelper::viewPath('includes.order-tracking-detail'))
        </div>
    </div>
</section>
