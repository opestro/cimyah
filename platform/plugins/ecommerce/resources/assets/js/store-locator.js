class StoreLocatorManagement {
    init() {
        $(document).on('click', '[data-bb-toggle="store-locator-show"]', (event) => {
            event.preventDefault()

            const $button = $(event.currentTarget)
            let $modalBody

            if ($button.data('type') === 'update') {
                $modalBody = $('#update-store-locator-modal .modal-body')
            } else {
                $modalBody = $('#add-store-locator-modal .modal-body')
            }

            $modalBody.html('')

            $httpClient
                .make()
                .get($button.data('load-form'))
                .then(({ data }) => {
                    $modalBody.html(data.data)
                    Botble.initResources()
                    $modalBody.closest('.modal.fade').modal('show')
                })
        })

        const createOrUpdateStoreLocator = ($button) => {
            const $form = $button.closest('.modal-content').find('form')

            $httpClient
                .make()
                .withButtonLoading($button)
                .post($form.prop('action'), $form.serialize())
                .then(({ data }) => {
                    Botble.showSuccess(data.message)

                    $('.store-locator-table').load(`${window.location.href} .store-locator-table > *`)
                    $button.closest('.modal.fade').modal('hide')
                })
        }

        $('#add-store-locator-modal').on('click', 'button[type="submit"]', (event) => {
            event.preventDefault()
            createOrUpdateStoreLocator($(event.currentTarget))
        })

        $('#update-store-locator-modal').on('click', 'button[type="submit"]', (event) => {
            event.preventDefault()

            createOrUpdateStoreLocator($(event.currentTarget))
        })

        $(document).on('click', '.btn-trigger-delete-store-locator', (event) => {
            event.preventDefault()
            $('#delete-store-locator-button').data('target', $(event.currentTarget).data('target'))
            $('#delete-store-locator-modal').modal('show')
        })

        $(document).on('click', '#delete-store-locator-button', (event) => {
            event.preventDefault()

            const $button = $(event.currentTarget)

            $httpClient
                .make()
                .withButtonLoading($button)
                .post($button.data('target'))
                .then(({ data }) => {
                    Botble.showSuccess(data.message)

                    $('.store-locator-table').load(`${window.location.href} .store-locator-table > *`)
                    $button.removeClass('button-loading')
                    $button.closest('.modal.fade').modal('hide')
                })
        })

        $(document).on('click', '#change-primary-store-locator-button', (event) => {
            event.preventDefault()

            const $button = $(event.currentTarget)
            const $form = $button.closest('.modal-content').find('form')

            $httpClient
                .make()
                .withButtonLoading($button)
                .post($form.prop('action'), $form.serialize())
                .then(({ data }) => {
                    Botble.showSuccess(data.message)
                    $('.store-locator-table').load(`${window.location.href} .store-locator-table > *`)
                    $button.removeClass('button-loading')
                    $button.closest('.modal.fade').modal('hide')
                })
        })
    }
}

$(() => {
    new StoreLocatorManagement().init()
})
