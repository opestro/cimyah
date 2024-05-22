class ShipmentManagement {
    init() {
        $(document).on('click', '[data-bb-toggle="update-shipping-status"]', () => {
            $('#update-shipping-status-modal').modal('show')
        })

        $(document).on('click', '[data-bb-toggle="update-shipping-cod-status"]', () => {
            $('#update-shipping-cod-status-modal').modal('show')
        })

        $(document).on('click', '#confirm-update-shipping-status-button', (event) => {
            event.preventDefault()

            const _self = $(event.currentTarget)
            const form = _self.closest('.modal-dialog').find('form')

            $httpClient
                .make()
                .withButtonLoading(_self)
                .post(form.prop('action'), form.serialize())
                .then(({ data }) => {
                    if (!data.error) {
                        $('.page-body').load(`${window.location.href} .page-body > *`)
                        Botble.showSuccess(data.message)
                        _self.closest('.modal').modal('hide')
                    } else {
                        Botble.showError(data.message)
                    }
                })
        })
    }
}

$(() => {
    new ShipmentManagement().init()
})
