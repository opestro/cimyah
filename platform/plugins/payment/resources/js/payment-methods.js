'use strict'

class PaymentMethodManagement {
    init() {
        $('.toggle-payment-item')
            .off('click')
            .on('click', (event) => {
                $(event.currentTarget).closest('tbody').find('.payment-content-item').toggleClass('hidden')

                window.EDITOR = new EditorManagement().init()
                window.EditorManagement = window.EditorManagement || EditorManagement
            })

        $('.disable-payment-item')
            .off('click')
            .on('click', (event) => {
                event.preventDefault()
                let _self = $(event.currentTarget)
                $('#confirm-disable-payment-method-modal').modal('show')
                $('#confirm-disable-payment-method-button').on('click', (event) => {
                    event.preventDefault()

                    $httpClient.make()
                        .withButtonLoading($(event.currentTarget))
                        .post(route('payments.methods.update.status'), {
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
                                $(event.currentTarget).closest('.modal').modal('hide')
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
                const form = _self.closest('form')

                if (typeof tinymce != 'undefined') {
                    for (let instance in tinymce.editors) {
                        if (tinymce.editors[instance].getContent) {
                            $(`#${instance}`).html(tinymce.editors[instance].getContent())
                        }
                    }
                }

                $httpClient.make()
                    .withButtonLoading(_self)
                    .post(form.prop('action') + window.location.search, form.serialize())
                    .then(({ data }) => {
                        _self.closest('tbody').find('.payment-name-label-group').removeClass('hidden')
                        _self
                            .closest('tbody')
                            .find('.method-name-label')
                            .text(_self.closest('form').find('input.input-name').val())
                        _self.closest('tbody').find('.disable-payment-item').removeClass('hidden')
                        _self.closest('tbody').find('.edit-payment-item-btn-trigger').removeClass('hidden')
                        _self.closest('tbody').find('.save-payment-item-btn-trigger').addClass('hidden')
                        _self.closest('tbody').find('.btn-text-trigger-update').removeClass('hidden')
                        _self.closest('tbody').find('.btn-text-trigger-save').addClass('hidden')
                        Botble.showSuccess(data.message)
                    })
            })
    }
}

$(() => {
    new PaymentMethodManagement().init()
})
