'use strict'

class PaymentMethodManagement {
    init() {
        $('.toggle-payment-item')
            .off('click')
            .on('click', (event) => {
                $(event.currentTarget).closest('tbody').find('.payment-content-item').toggleClass('hidden')
            })
        $('.disable-payment-item')
            .off('click')
            .on('click', (event) => {
                event.preventDefault()
                const _self = $(event.currentTarget)

                $('#confirm-disable-payment-method-modal').modal('show')

                $('#confirm-disable-payment-method-button').on('click', (event) => {
                    event.preventDefault()

                    $httpClient
                        .make()
                        .withButtonLoading($(event.currentTarget))
                        .post($('div[data-disable-payment-url]').data('disable-payment-url'), {
                            type: _self.closest('form').find('.payment_type').val(),
                        })
                        .then(({ data }) => {
                            if (!data.error) {
                                _self.closest('tbody').find('.payment-name-label-group').addClass('hidden')
                                _self.closest('tbody').find('.edit-payment-item-btn-trigger').addClass('hidden')
                                _self.closest('tbody').find('.save-payment-item-btn-trigger').removeClass('hidden')
                                _self.closest('tbody').find('.btn-text-trigger-update').addClass('hidden')
                                _self.closest('tbody').find('.btn-text-trigger-save').removeClass('hidden')
                                _self.addClass('hidden')
                                $('#confirm-disable-payment-method-modal').modal('hide')
                                Botble.showSuccess(data.message)
                            } else {
                                Botble.showError(data.message)
                            }
                        })
                })
            })

        $('.save-payment-item')
            .off('click')
            .on('click', (event) => {
                event.preventDefault()
                const _self = $(event.currentTarget)

                $httpClient
                    .make()
                    .withButtonLoading(_self)
                    .post(
                        $('div[data-update-payment-url]').data('update-payment-url'),
                        _self.closest('form').serialize()
                    )
                    .then(({ data }) => {
                        if (!data.error) {
                            _self.closest('tbody').find('.payment-name-label-group').removeClass('hidden')
                            _self
                                .closest('tbody')
                                .find('.method-name-label')
                                .text(_self.closest('form').find('input[name=name]').val())
                            _self.closest('tbody').find('.disable-payment-item').removeClass('hidden')
                            _self.closest('tbody').find('.edit-payment-item-btn-trigger').removeClass('hidden')
                            _self.closest('tbody').find('.save-payment-item-btn-trigger').addClass('hidden')
                            _self.closest('tbody').find('.btn-text-trigger-update').removeClass('hidden')
                            _self.closest('tbody').find('.btn-text-trigger-save').addClass('hidden')
                            Botble.showSuccess(data.message)
                        } else {
                            Botble.showError(data.message)
                        }
                    })
            })
    }
}

$(() => {
    new PaymentMethodManagement().init()
})
