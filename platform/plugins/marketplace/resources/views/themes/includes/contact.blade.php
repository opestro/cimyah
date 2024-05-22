<div class="mb-4 row">
    <div class="col-md-6">
        <h3 class="fs-4">{{ __('Email :store', ['store' => $store->name]) }}</h3>
        <p>{{ __('All messages are recorded and spam is not tolerated. Your email address will be shown to the recipient.') }}</p>
        {!! $contactForm->renderForm() !!}
    </div>
</div>

<script>
    'use strict';

    window.addEventListener('load', function() {
        $(document).on('submit', '.bb-contact-store-form', function (e) {
            e.preventDefault()

            var $form = $(e.currentTarget)
            var $button = $form.find('button[type="submit"]')

            $.ajax({
                url: $form.prop('action'),
                method: $form.prop('method'),
                data: $form.serialize(),
                beforeSend: function () {
                    $button.prop('disabled', true).addClass('btn-loading');
                },
                success: function (response) {
                    $form[0].reset();

                    if (typeof Theme !== 'undefined') {
                        if (response.error) {
                            Theme.showError(response.message);
                        } else {
                            Theme.showSuccess(response.message);
                        }
                    }
                },
                error: function (response) {
                    if (typeof Theme !== 'undefined') {
                        Theme.handleError(response);
                    }
                },
                complete: function () {
                    if (typeof refreshRecaptcha !== 'undefined') {
                        refreshRecaptcha()
                    }

                    $button.prop('disabled', false).removeClass('btn-loading');
                }
            });
        });
    });
</script>
