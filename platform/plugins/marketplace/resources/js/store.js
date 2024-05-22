$(() => {
    $(document)
        .on('keyup', '#shop-url', function () {
            const displayURL = $(this).closest('.shop-url-wrapper').find('small')
            displayURL.html(`${displayURL.data('base-url')}/<strong>${$(this).val().toLowerCase()}</strong>`)
        })

        .on('change', '#shop-url', function () {
            const $form = $(this).closest('form')
            const $button = $form.find('button[type=submit]')
            const $urlStatus = $form.find('.form-label-description')

            $button.addClass('btn-disabled').prop('disabled', true)

            $httpClient.make()
                .withLoading($form.find('.shop-url-wrapper'))
                .post($(this).data('url'), {
                    url: $(this).val(),
                    reference_id: $form.find('input[name=reference_id]').val(),
                })
                .then(({ data }) => {
                    if (data.error) {
                        $urlStatus.removeClass('text-success').addClass('text-danger').text(data.message)
                    } else {
                        $urlStatus.removeClass('text-danger').addClass('text-success').text(data.message)
                        $button.prop('disabled', false).removeClass('btn-disabled')
                    }
                })
        })
})
