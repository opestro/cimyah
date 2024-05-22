$(() => {
    $(document).on('click', 'input[name=is_vendor]', (e) => {
        const currentTarget = $(e.currentTarget)

        if (currentTarget.val() == 1) {
            $('[data-bb-toggle="vendor-info"]').slideDown()
        } else {
            $('[data-bb-toggle="vendor-info"]').slideUp()
            currentTarget.closest('form').find('button[type=submit]').prop('disabled', false)
        }
    })

    $('form.js-base-form input[name="shop_url"]')
        .on('keyup', (e) => {
            const currentTarget = $(e.currentTarget)
            const form = currentTarget.closest('form')
            const slug = form.find('[data-slug-value]')

            slug.html(
                `${slug.data('base-url')}/<strong>${currentTarget.val().substring(0, 100).toLowerCase()}</strong>`
            )
        })
        .on('change', (e) => {
            const currentTarget = $(e.currentTarget)
            const form = currentTarget.closest('form')
            const url = currentTarget.val()

            if (!url) {
                return
            }

            const slug = form.find('[data-slug-value]')

            $.ajax({
                url: currentTarget.data('url'),
                type: 'POST',
                data: { url },
                beforeSend: () => {
                    currentTarget.prop('disabled', true)
                    form.find('button[type=submit]').prop('disabled', true)
                },
                success: ({ error, message, data }) => {
                    if (error) {
                        currentTarget.addClass('is-invalid').removeClass('is-valid')
                        $('.shop-url-status').removeClass('text-success').addClass('text-danger').text(message)
                    } else {
                        $('.shop-url-status').removeClass('text-danger').addClass('text-success').text(message)
                        form.find('button[type=submit]').prop('disabled', false)
                    }

                    if (data?.slug) {
                        slug.html(
                            `${slug.data('base-url')}/<strong>${data.slug.substring(0, 100).toLowerCase()}</strong>`
                        )
                    }
                },
                complete: () => currentTarget.prop('disabled', false),
            })
        })
})
