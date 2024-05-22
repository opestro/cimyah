class ShippingManagement {
    init() {
        $(document).on('click', '.btn-confirm-delete-region-item-modal-trigger', (event) => {
            event.preventDefault()
            let $modal = $('#confirm-delete-region-item-modal')
            $modal.find('.region-item-label').text($(event.currentTarget).data('name'))
            $modal.find('#confirm-delete-region-item-button').data('id', $(event.currentTarget).data('id'))
            $modal.modal('show')
        })

        $(document).on('click', '#confirm-delete-region-item-button', (event) => {
            event.preventDefault()

            const _self = $(event.currentTarget)

            $httpClient
                .make()
                .withButtonLoading(_self)
                .post($('div[data-delete-region-item-url]').data('delete-region-item-url'), {
                    _method: 'DELETE',
                    id: _self.data('id'),
                })
                .then(({ data }) => {
                    if (!data.error) {
                        $(`.wrap-table-shipping-${_self.data('id')}`).remove()

                        Botble.showSuccess(data.message)
                    } else {
                        Botble.showError(data.message)
                    }
                    $('#confirm-delete-region-item-modal').modal('hide')

                    if ($('.wrapper-content .p-3').children().length < 1) {
                        $('.wrapper-content').hide()
                    }
                })
        })

        $(document).on('click', '.btn-confirm-delete-price-item-modal-trigger', (event) => {
            event.preventDefault()
            let $modal = $('#confirm-delete-price-item-modal')
            $modal.find('.region-price-item-label').text($(event.currentTarget).data('name'))
            $modal.find('#confirm-delete-price-item-button').data('id', $(event.currentTarget).data('id'))
            $modal.modal('show')
        })

        $(document).on('click', '#confirm-delete-price-item-button', (event) => {
            event.preventDefault()

            const _self = $(event.currentTarget)

            $httpClient
                .make()
                .withButtonLoading(_self)
                .post($('div[data-delete-rule-item-url]').data('delete-rule-item-url'), {
                    _method: 'DELETE',
                    id: _self.data('id'),
                })
                .then(({ data }) => {
                    if (!data.error) {
                        $(`.box-table-shipping-item-${_self.data('id')}`).remove()
                        if (data.data.count === 0) {
                            $(`.wrap-table-shipping-${data.data.shipping_id}`).remove()
                        }
                        Botble.showSuccess(data.message)
                    } else {
                        Botble.showError(data.message)
                    }
                    $('#confirm-delete-price-item-modal').modal('hide')
                })
        })

        let saveRuleItem = ($this, $form, method, shippingId) => {
            $(document).find('.field-has-error').removeClass('field-has-error')

            const _self = $this

            let formData = []

            if (method !== 'POST') {
                formData._method = method
            }

            $.each($form.serializeArray(), (index, el) => {
                if (el.name === 'from' || el.name === 'to' || el.name === 'price') {
                    if (el.value) {
                        el.value = parseFloat(el.value.replace(',', '')).toFixed(2)
                    }
                }
                formData[el.name] = el.value
            })

            if (shippingId) {
                formData.shipping_id = shippingId
            }

            formData = Botble.unmaskInputNumber($form, formData)

            formData = $.extend({}, formData)

            $httpClient
                .make()
                .withButtonLoading(_self)
                .post($form.prop('action'), formData)
                .then(({ data }) => {
                    if (!data.error) {
                        Botble.showSuccess(data.message)
                        if (data?.data?.rule?.shipping_id && data?.data?.html) {
                            const $box = $(`.wrap-table-shipping-${data.data.rule.shipping_id}`)
                            const $item = $box.find(`.box-table-shipping-item-${data.data.rule.id} .p-3`)

                            if ($item.length) {
                                $item.replaceWith(data.data.html)
                            } else {
                                $box.append(data.data.html)
                            }
                            Botble.initResources()
                        }
                    } else {
                        Botble.showError(data.message)
                    }

                    if (shippingId) {
                        _self.closest('.modal').modal('hide')
                    }
                })
        }

        $(document).on('click', '.btn-save-rule', (event) => {
            event.preventDefault()
            const $this = $(event.currentTarget)
            saveRuleItem($this, $this.closest('form'), 'PUT', null)
        })

        $(document).on('change', '.select-rule-type', (event) => {
            event.preventDefault()
            const _self = $(event.currentTarget)

            const $box = _self.closest('form')
            const $option = _self.find('option:selected')

            if ($option.data('show-from-to')) {
                $box.find('.rule-from-to-inputs').show()
            } else {
                $box.find('.rule-from-to-inputs').hide()
            }

            $box.find('.unit-item-label').text($option.data('unit'))
            $box.find('.rule-from-to-label').text($option.data('text'))
        })

        $(document).on('keyup', '.input-sync-item', (event) => {
            const $this = $(event.currentTarget)
            let number = $this.val()
            if (!number || isNaN(number)) {
                number = 0
            }
            $this
                .closest('.input-shipping-sync-wrapper')
                .find($this.data('target'))
                .text(Botble.numberFormat(parseFloat(number), 2))
        })

        $(document).on('keyup', '.input-sync-text-item', (event) => {
            const $this = $(event.currentTarget)
            $this.closest('.input-shipping-sync-wrapper').find($this.data('target')).text($this.val())
        })

        $(document).on('keyup', '.input-to-value-field', (event) => {
            const $this = $(event.currentTarget)
            const $parent = $this.closest('.input-shipping-sync-wrapper')
            if ($this.val()) {
                $parent.find('.rule-to-value-wrap').removeClass('hidden')
                $parent.find('.rule-to-value-missing').addClass('hidden')
            } else {
                $parent.find('.rule-to-value-wrap').addClass('hidden')
                $parent.find('.rule-to-value-missing').removeClass('hidden')
            }
        })

        $(document).on('click', '.btn-add-shipping-rule-trigger', (event) => {
            event.preventDefault()
            const $this = $(event.currentTarget)
            const $modal = $('#add-shipping-rule-item-modal')
            $('#add-shipping-rule-item-button').data('shipping-id', $this.data('shipping-id'))
            $modal.find('select[name=type] option[disabled]').prop('disabled', false)
            if (!$this.data('country')) {
                $modal.find('select[name=type] option[value=base_on_zip_code]').prop('disabled', true)
            }

            $modal.find('input[name=name]').val('')
            $modal.find('select[name=type]').val('').trigger('change')
            $modal.find('input[name=from]').val('0')
            $modal.find('input[name=to]').val('')
            $modal.find('input[name=price]').val('0')
            $modal.modal('show')
        })

        $(document).on('click', '.btn-shipping-rule-item-trigger', (event) => {
            event.preventDefault()

            const _self = $(event.currentTarget)
            const $modal = $('#form-shipping-rule-item-detail-modal')

            $modal.modal('show')
            $modal.find('.modal-title strong').html('')
            $modal.find('.modal-body')
                .html(`<div class='w-100 text-center py-3'><div class='spinner-border' role='status'>
                    <span class='visually-hidden'>Loading...</span>
                  </div></div>`)

            $httpClient
                .make()
                .withButtonLoading(_self)
                .get(_self.data('url'))
                .then(({ data }) => {
                    if (!data.error) {
                        $modal.find('.modal-body').html(data.data.html)
                        $modal.find('.modal-title strong').html(data.message)
                        Botble.initResources()
                    } else {
                        Botble.showError(data.message)
                    }
                })
        })

        $(document).on('click', '#save-shipping-rule-item-detail-button', (event) => {
            event.preventDefault()

            const _self = $(event.currentTarget)
            const $modal = $('#form-shipping-rule-item-detail-modal')
            const $form = $modal.find('form')

            const allowedMethods = ['get', 'post', 'put', 'delete']
            const method = $form.prop('method').toLowerCase()

            if (!allowedMethods.includes(method)) {
                Botble.showError('This method is not supported.')

                return
            }

            let formData = new FormData($form[0])

            formData = Botble.unmaskInputNumber($form, formData)

            $httpClient
                .make()
                .withButtonLoading(_self)
                [method]($form.prop('action'), formData)
                .then(({ data }) => {
                    if (!data.error) {
                        const $table = $(`.table-shipping-rule-${data.data.shipping_rule_id}`)
                        if ($table.find(`.shipping-rule-item-${data.data.id}`).length) {
                            $table.find(`.shipping-rule-item-${data.data.id}`).replaceWith(data.data.html)
                        } else {
                            $table.prepend(data.data.html)
                        }
                        $modal.modal('hide')
                        Botble.showSuccess(data.message)
                    } else {
                        Botble.showError(data.message)
                    }
                })
        })

        $(document).on('click', '.btn-confirm-delete-rule-item-modal-trigger', (event) => {
            event.preventDefault()
            let $modal = $('#confirm-delete-shipping-rule-item-modal')
            $modal.find('.item-label').text($(event.currentTarget).data('name'))
            $modal.find('#confirm-delete-shipping-rule-item-button').data('url', $(event.currentTarget).data('section'))
            $modal.modal('show')
        })

        $(document).on('click', '#confirm-delete-shipping-rule-item-button', (event) => {
            event.preventDefault()

            const _self = $(event.currentTarget)

            $httpClient
                .make()
                .withButtonLoading(_self)
                .post(_self.data('url'), {
                    _method: 'DELETE',
                })
                .then(({ data }) => {
                    if (!data.error) {
                        const $table = $(`.table-shipping-rule-${data.data.shipping_rule_id}`)
                        if ($table.find(`.shipping-rule-item-${data.data.id}`).length) {
                            $table.find(`.shipping-rule-item-${data.data.id}`).fadeOut(500, function () {
                                $(this).remove()
                            })
                        }
                        Botble.showSuccess(data.message)
                    } else {
                        Botble.showError(data.message)
                    }
                    $('#confirm-delete-shipping-rule-item-modal').modal('hide')
                })
        })

        Botble.select($(document).find('.select-country-search'))

        $(document).on('click', '.btn-select-country', (event) => {
            event.preventDefault()
            $('#select-country-modal').modal('show')
        })

        $(document).on('click', '#add-shipping-region-button', (event) => {
            event.preventDefault()

            const _self = $(event.currentTarget)
            const $form = _self.closest('.modal-content').find('form')

            $httpClient
                .make()
                .withButtonLoading(_self)
                .post($form.prop('action'), $form.serialize())
                .then(({ data }) => {
                    Botble.showSuccess(data.message)
                    $('.wrapper-content').load(`${window.location.href} .wrapper-content > *`)
                    $('#select-country-modal').modal('hide')
                    $('.wrapper-content').show()
                })
        })

        $(document).on('click', '#add-shipping-rule-item-button', (event) => {
            event.preventDefault()

            const _self = $(event.currentTarget)

            saveRuleItem(_self, _self.closest('.modal-content').find('form'), 'POST', _self.data('shipping-id'))
        })

        $(document).on('keyup', '.base-price-rule-item', (event) => {
            const _self = $(event.currentTarget)
            let basePrice = _self.val()

            if (!basePrice || isNaN(basePrice)) {
                basePrice = 0
            }

            $.each($(document).find('.support-shipping .rule-adjustment-price-item'), (index, item) => {
                let adjustmentPrice = $(item).closest('tr').find('.shipping-price-district').val()
                if (!adjustmentPrice || isNaN(adjustmentPrice)) {
                    adjustmentPrice = 0
                }

                $(item).text(Botble.numberFormat(parseFloat(basePrice) + parseFloat(adjustmentPrice)), 2)
            })
        })

        $(document).on('change', 'select[name=shipping_rule_id].shipping-rule-id', function (e) {
            e.preventDefault()

            const _self = $(e.currentTarget)
            const $form = _self.closest('form')
            let $country = $form.find('select[data-type="country"]')
            const val = _self.find('option:selected').data('country')

            if ($country.length) {
                if ($country.val() !== val) {
                    $country.val(val).trigger('change')
                }
            } else {
                $country = $form.find('input[name="country"]')
                if ($country.length && $country.val() !== val) {
                    $country.val(val)
                }
            }
        })

        $(document).on('click', '.table-shipping-rule-items .shipping-rule-load-items', (event) => {
            event.preventDefault()

            const _self = $(event.currentTarget)
            const $table = _self.closest('.table-shipping-rule-items')

            loadRuleItems(_self.attr('href'), $table, _self)
        })

        $(document).on('click', '.table-shipping-rule-items a.page-link', (event) => {
            event.preventDefault()

            const _self = $(event.currentTarget)
            const $table = _self.closest('.table-shipping-rule-items')

            loadRuleItems(_self.attr('href'), $table, _self)
        })

        $(document).on('change', '.table-shipping-rule-items .number-record .numb', (e) => {
            e.preventDefault()

            const $this = $(e.currentTarget)
            const perPage = $this.val()

            if (!isNaN(perPage) && perPage > 0) {
                const $table = $this.closest('.table-shipping-rule-items')
                const $th = $table.find('thead tr th[data-column][data-dir]')
                const data = { per_page: perPage }

                if ($th.length) {
                    data.order_by = $th.data('column')
                    data.order_dir = $th.data('dir') || 'DESC'
                }
                loadRuleItems($table.data('url'), $table, $this, data)
            } else {
                $this.val($this.attr('min') || 12).trigger('change')
            }
        })

        $(document).on('click', '.table-shipping-rule-items thead tr th[data-column]', (e) => {
            e.preventDefault()

            const _self = $(e.currentTarget)
            let orderBy = _self.data('column')
            let orderDir = _self.data('dir') || 'ASC'
            const $table = _self.closest('.table-shipping-rule-items')
            const $numb = $table.find('.number-record .numb')
            const perPage = $numb.val()
            orderDir = orderDir === 'ASC' ? 'DESC' : 'ASC'

            loadRuleItems($table.data('url'), $table, _self, {
                order_by: orderBy,
                order_dir: orderDir,
                per_page: perPage,
            })
        })

        function loadRuleItems(url, $table, $button, data = {}) {
            $httpClient
                .make()
                .withButtonLoading($button)
                .get(url, data)
                .then(({ data }) => {
                    if (!data.error) {
                        $table.replaceWith(data.data.html)
                    } else {
                        Botble.showError(data.message)
                    }
                })
        }
    }
}

$(() => {
    new ShippingManagement().init()
})
