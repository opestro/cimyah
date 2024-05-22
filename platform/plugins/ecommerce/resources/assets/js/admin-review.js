$(() => {
    const toggleReviewStatus = (url, button) => {
        Botble.showButtonLoading(button)

        $httpClient
            .make()
            .post(url)
            .then(({ data }) => {
                if (data.error) {
                    Botble.showError(data.message)
                    return
                }
                Botble.showSuccess(data.message)
                $('#review-section-wrapper').load(`${window.location.href} #review-section-wrapper > *`)

                button.closest('.modal').modal('hide')
            })
            .finally(() => Botble.hideButtonLoading(button))
    }

    const toggleEditReply = () => {
        const $form = $('#review-section-wrapper form')

        $form.find('[data-bb-toggle="edit-reply"]').toggle()
        $form.find('[data-bs-target="#delete-review-reply-modal"]').toggle()
        $form.find('.reply-message').toggle()
        $form.find('.reply-form').toggle()
        $form.find('[data-bb-toggle="update-reply"]').toggle()
        $form.find('[data-bb-toggle="cancel-edit-reply"]').toggle()
    }

    $(document)
        .on('click', '[data-bb-toggle="review-delete"]', (event) => {
            $('#confirm-delete-review-button').data('target', $(event.currentTarget).data('target'))
            $('#delete-review-modal').modal('show')
        })
        .on('click', '#confirm-delete-review-button', (event) => {
            const _self = $(event.currentTarget)
            const url = _self.data('target')

            $httpClient
                .make()
                .withButtonLoading(_self)
                .delete(url)
                .then(({ data }) => {
                    Botble.showSuccess(data.message)

                    _self.closest('.modal').modal('hide')

                    setTimeout(() => (window.location.href = data?.data?.next_url), 2000)
                })
                .finally(() => Botble.hideButtonLoading(_self))
        })
        .on('click', '[data-bb-toggle="review-unpublish"]', (event) => {
            const button = $(event.currentTarget)

            toggleReviewStatus(route('reviews.unpublish', button.data('id')), button)
        })
        .on('click', '[data-bb-toggle="review-publish"]', (event) => {
            const button = $(event.currentTarget)

            toggleReviewStatus(route('reviews.publish', button.data('id')), button)
        })
        .on('click', '[data-bb-toggle="review-reply-delete"]', (event) => {
            const currentTarget = $(event.currentTarget)
            const form = currentTarget.closest('form')

            $httpClient
                .make()
                .withButtonLoading(currentTarget)
                .delete(form.prop('action'))
                .then(({ data }) => {
                    Botble.showSuccess(data.message)

                    form.closest('.modal').modal('hide')

                    setTimeout(() => window.location.reload(), 2000)
                })
        })
        .on('click', '[data-bb-toggle="edit-reply"]', (event) => {
            const currentTarget = $(event.currentTarget)

            toggleEditReply(currentTarget)
        })
        .on('click', '[data-bb-toggle="cancel-edit-reply"]', (event) => {
            const currentTarget = $(event.currentTarget)

            toggleEditReply(currentTarget)
        })
})
