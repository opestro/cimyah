$(() => {
    'use strict'

    const loadAjaxCart = (data) => {
        $('.cartmini__area').html(data.cart_mini)
        $('[data-bb-value="cart-count"]').text(data.count)

        if ($('.tp-cart-area').length) {
            $('.tp-cart-area').replaceWith(data.cart_content)
        }

        if (typeof Theme.lazyLoadInstance !== 'undefined') {
            Theme.lazyLoadInstance.update()
        }
    }

    const handleUpdateCart = (element) => {
        let form

        if (element) {
            form = $(element).closest('form')
        } else {
            form = $('form.cart-form')
        }

        $.ajax({
            type: 'POST',
            url: form.prop('action'),
            data: form.serialize(),
            success: ({ error, message, data }) => {
                if (error) {
                    Theme.showError(message)
                }

                loadAjaxCart(data)
            },
            error: (error) => Theme.handleError(error),
        })
    }

    /**
     * @param {Array<Number>} data
     * @param {jQuery} element
     */
    window.onBeforeChangeSwatches = (data, element) => {
        const form = element.closest('form')

        if (data) {
            form.find('button[type="submit"]').prop('disabled', true)
            form.find('button[data-bb-toggle="add-to-cart"]').prop('disabled', true)
        }
    }

    /**
     * @param {{data: Object, error: Boolean, message: String}} response
     * @param {jQuery} element
     */
    window.onChangeSwatchesSuccess = (response, element) => {
        if (!response) {
            return
        }

        const $product = $('.bb-product-detail')
        const $form = element.closest('form')
        const $button = $form.find('button[type="submit"]')
        const $quantity = $form.find('input[name="qty"]')
        const $available = $product.find('.number-items-available')
        const $sku = $product.find('[data-bb-value="product-sku"]')

        const { error, data } = response

        if (error) {
            $button.prop('disabled', true)
            $quantity.prop('disabled', true)

            $form.find('input[name="id"]').val('')

            return
        }

        $button.prop('disabled', false)
        $quantity.prop('disabled', false)
        $form.find('input[name="id"]').val(data.id)

        $product.find('[data-bb-value="product-price"]').text(data.display_sale_price)

        if (data.original_price !== data.price) {
            $product.find('[data-bb-value="product-original-price"]').text(data.display_price).show()
        } else {
            $product.find('[data-bb-value="product-original-price"]').hide()
        }

        if (data.sku) {
            $sku.text(data.sku)
            $sku.closest('div').show()
        } else {
            $sku.closest('div').hide()
        }

        if (data.error_message) {
            $button.prop('disabled', true)
            $quantity.prop('disabled', true)

            $available.html(`<span class="text-danger">${data.error_message}</span>`).show()
        } else if (data.success_message) {
            $available.html(`<span class="text-success">${data.success_message}</span>`).show()
        } else {
            $available.html('').hide()
        }

        $product.find('.bb-product-attribute-swatch-item').removeClass('disabled')
        $product.find('.bb-product-attribute-swatch-list select option').prop('disabled', false)

        const unavailableAttributeIds = data.unavailable_attribute_ids || []

        if (unavailableAttributeIds.length) {
            unavailableAttributeIds.map((id) => {
                let $swatchItem = $product.find(`.bb-product-attribute-swatch-item[data-id="${id}"]`)

                if ($swatchItem.length) {
                    $swatchItem.addClass('disabled')
                    $swatchItem.find('input').prop('checked', false)
                } else {
                    $swatchItem = $product.find(`.bb-product-attribute-swatch-list select option[data-id="${id}"]`)

                    if ($swatchItem.length) {
                        $swatchItem.prop('disabled', true)
                    }
                }
            })
        }

        let imageHtml = ''
        let thumbHtml = ''

        if (!data.image_with_sizes.origin.length) {
            data.image_with_sizes.origin.push(siteConfig.img_placeholder)
        } else {
            data.image_with_sizes.origin.forEach(function (item) {
                imageHtml += `
                    <a href="${item}">
                        <img src="${item}" alt="${data.name}">
                    </a>
                `
            })
        }

        if (!data.image_with_sizes.thumb.length) {
            data.image_with_sizes.thumb.push(siteConfig.img_placeholder)
        } else {
            data.image_with_sizes.thumb.forEach(function (item) {
                thumbHtml += `
                    <div>
                        <img src="${item}" alt="${data.name}">
                    </div>
                `
            })
        }

        $product.find('.bb-product-gallery-thumbnails').slick('unslick').html(thumbHtml)

        if ($('.bb-quick-view-gallery-images').length) {
            $('.bb-quick-view-gallery-images').slick('unslick').html(imageHtml)
        }

        $product.find('.bb-product-gallery-images').slick('unslick').html(imageHtml)

        if (typeof EcommerceApp !== 'undefined') {
            EcommerceApp.initProductGallery()
        }
    }

    $(document)
        .on('click', '[data-bb-toggle="remove-coupon"]', (e) => {
            e.preventDefault()

            const currentTarget = $(e.currentTarget)

            $.ajax({
                url: currentTarget.prop('href'),
                type: 'POST',
                success: ({ error, message }) => {
                    if (error) {
                        Theme.showError(message)

                        return
                    }

                    Theme.showSuccess(message)

                    handleUpdateCart()
                },
                error: (error) => Theme.handleError(error),
            })
        })
        .on('click', '[data-bb-toggle="decrease-qty"]', (e) => {
            const $input = $(e.currentTarget).parent().find('input')

            let count = parseInt($input.val()) - 1
            count = count < 1 ? 1 : count
            $input.val(count)
            $input.trigger('change')
        })
        .on('click', '[data-bb-toggle="increase-qty"]', (e) => {
            const $input = $(e.currentTarget).parent().find('input')

            const max = $input.prop('max')

            if (max && parseInt($input.val()) >= parseInt(max)) {
                return
            }

            $input.val(parseInt($input.val()) + 1)
            $input.trigger('change')
        })
        .on('change', '[data-bb-toggle="update-cart"]', (e) => {
            handleUpdateCart(e.currentTarget)
        })
        .on('click', '[data-bb-toggle="change-product-filter-layout"]', (e) => {
            e.preventDefault()

            const currentTarget = $(e.currentTarget)

            currentTarget.addClass('active')
            currentTarget.closest('li').siblings().find('button').removeClass('active')

            $('.bb-product-form-filter').find('[name="layout"]').val(currentTarget.data('value')).trigger('change')
        })
        .on('click', '[data-bb-toggle="copy-coupon"]', async (e) => {
            e.preventDefault()

            const currentTarget = $(e.currentTarget)
            const value = currentTarget.data('value')
            const previousText = currentTarget.find('span').text()

            if (navigator.clipboard) {
                await navigator.clipboard.writeText(value)
            } else {
                const tempInput = document.createElement('input')
                tempInput.value = value
                document.body.appendChild(tempInput)
                tempInput.select()
                document.execCommand('copy')
                document.body.removeChild(tempInput)
            }

            currentTarget.find('span').text(currentTarget.data('copied-message'))

            setTimeout(() => currentTarget.find('span').text(previousText), 2000)
        })
        .on('click', '[data-bb-toggle="scroll-to-review"]', (e) => {
            if ($('.nav-tabs button#nav-review-tab').length) {
                e.preventDefault()

                const $tab = $('.nav-tabs button#nav-review-tab')
                const $container = $('.product-review-container')

                if ($tab.length && $container.length) {
                    $tab.tab('show')

                    $('html, body').animate({
                        scrollTop: $container.offset().top - 100,
                    })
                }
            }
        })
        .on('show.bs.modal', '#product-quick-view-modal', (e) => {
            const modal = $(e.currentTarget)
            const trigger = $(e.relatedTarget)

            $.ajax({
                url: trigger.data('url') || trigger.prop('href'),
                type: 'GET',
                beforeSend: () => {
                    trigger.addClass('btn-loading')
                    modal.find('.modal-content').css('min-height', '40rem').html('<div class="loading-spinner"></div>')
                },
                success: ({ error, data }) => {
                    if (error) {
                        return
                    }

                    modal.find('.modal-content').css('min-height', '0').html(data)

                    if (typeof Theme.lazyLoadInstance !== 'undefined') {
                        Theme.lazyLoadInstance.update()
                    }

                    setTimeout(() => { EcommerceApp.initProductGallery(true) }, 100)

                    document.dispatchEvent(new CustomEvent('ecommerce.quick-view.initialized'))
                },
                complete: () => trigger.removeClass('btn-loading'),
            })
        })
        .on('submit', 'form#counpon-form', (e) => {
            e.preventDefault()

            const currentTarget = $(e.currentTarget)
            const button = currentTarget.find('button[type="submit"]')

            $.ajax({
                url: currentTarget.prop('action'),
                type: 'POST',
                data: currentTarget.serialize(),
                beforeSend: () => button.prop('disabled', true).addClass('btn-loading'),
                success: ({ error, message }) => {
                    if (error) {
                        Theme.showError(message)

                        return
                    }

                    Theme.showSuccess(message)

                    handleUpdateCart()
                },
                error: (error) => Theme.handleError(error),
                complete: () => button.prop('disabled', false).removeClass('btn-loading'),
            })
        })
        .on('keyup', 'form#counpon-form input', (e) => {
            const currentTarget = $(e.currentTarget)

            currentTarget.closest('form').find('button[type="submit"]').prop('disabled', !currentTarget.val())
        })
        .on('click', '.product-form button[type="submit"]', (e) => {
            e.preventDefault()

            const currentTarget = $(e.currentTarget)
            const form = currentTarget.closest('form')
            const data = form.serializeArray()

            if (form.find('input[name="id"]').val() === '') {
                return
            }

            data.push({ name: 'checkout', value: currentTarget.prop('name') === 'checkout' ? 1 : 0 })

            $.ajax({
                type: 'POST',
                url: form.prop('action'),
                data: data,
                beforeSend: () => {
                    currentTarget.prop('disabled', true).addClass('btn-loading')
                },
                success: ({ error, message, data }) => {
                    if (error) {
                        Theme.showError(message)

                        if (data?.next_url !== undefined) {
                            window.location.href = data.next_url
                        }

                        return
                    }

                    form.find('input[name="qty"]').val(1)

                    if (data?.next_url !== undefined) {
                        window.location.href = data.next_url
                    } else {
                        loadAjaxCart(data)

                        $('.cartmini__area').addClass('cartmini-opened')
                        $('.body-overlay').addClass('opened')
                    }
                },
                error: (error) => Theme.handleError(error),
                complete: () => currentTarget.prop('disabled', false).removeClass('btn-loading'),
            })
        })
        .on('click', '[data-bb-toggle="scroll-to-review"]', (e) => {
            if ($('.nav-tabs button#nav-review-tab').length) {
                e.preventDefault()

                const $tab = $('.nav-tabs button#nav-review-tab')
                const $container = $('.product-review-container')

                if ($tab.length && $container.length) {
                    $tab.tab('show')

                    $('html, body').animate({
                        scrollTop: $container.offset().top - 100,
                    })
                }
            }
        })
        .on('click', '.js-sale-popup-quick-view-button', (e) => {
            e.preventDefault()

            $('#product-quick-view-modal').modal('show', e.currentTarget)
        })
        .on('change', '.tp-shop-top-select select', (e) => {
            const currentTarget = $(e.currentTarget)

            const form = $('.bb-product-form-filter')

            form.find(`input[name="${currentTarget.prop('name')}"]`)
                .val(currentTarget.val())
                .trigger('submit')
        })
        .on('click', '.bb-product-items-wrapper .pagination a', (e) => {
            e.preventDefault()

            const currentTarget = $(e.currentTarget)

            const url = new URL(currentTarget.prop('href'))
            const page = url.searchParams.get('page')

            $('.bb-product-form-filter').find('[name="page"]').val(page).trigger('change')
        })
        .on('submit', 'form.subscribe-form', (e) => {
            e.preventDefault()

            const $form = $(e.currentTarget)
            const $button = $form.find('button[type=submit]')

            $.ajax({
                type: 'POST',
                cache: false,
                url: $form.prop('action'),
                data: new FormData($form[0]),
                contentType: false,
                processData: false,
                beforeSend: () => $button.prop('disabled', true).addClass('btn-loading'),
                success: ({ error, message }) => {
                    if (error) {
                        Theme.showError(message)

                        return
                    }

                    $form.find('input').val('')

                    Theme.showSuccess(message)

                    document.dispatchEvent(new CustomEvent('newsletter.subscribed'))
                },
                error: (error) => {
                    if (typeof refreshRecaptcha !== 'undefined') {
                        refreshRecaptcha()
                    }

                    Theme.handleError(error)
                },
                complete: () => {
                    if (typeof refreshRecaptcha !== 'undefined') {
                        refreshRecaptcha()
                    }

                    $button.prop('disabled', false).removeClass('btn-loading')
                },
            })
        })
        .on('click', '[data-bb-toggle="product-tab"]', (e) => {
            e.preventDefault()

            const currentTarget = $(e.currentTarget)

            const tabPane = currentTarget.closest('.tp-product-area').find('#productTabContent .tab-pane')
            const wrapper = tabPane.closest('.tp-product-area')
            const tooltip = currentTarget.find('span.tp-product-tab-tooltip')

            $.ajax({
                url: `${currentTarget.closest('#productTab').data('ajax-url')}&type=${currentTarget.data('bb-value')}`,
                method: 'GET',
                dataType: 'json',
                beforeSend: () => {
                    tooltip.text('...')
                    wrapper.append('<div class="loading-spinner"></div>')
                },
                success: ({ data }) => {
                    tooltip.text(data.count)
                    tabPane.html(data.html)

                    if (typeof Theme.lazyLoadInstance !== 'undefined') {
                        Theme.lazyLoadInstance.update()
                    }
                },
                error: (error) => Theme.handleError(error),
                complete: () => $('.loading-spinner').remove(),
            })
        })
        .on('submit', '.contact-form', (e) => {
            e.preventDefault()

            const $form = $(e.currentTarget)
            const $button = $form.find('button[type=submit]')

            $.ajax({
                type: 'POST',
                cache: false,
                url: $form.prop('action'),
                data: new FormData($form[0]),
                contentType: false,
                processData: false,
                beforeSend: () => $button.addClass('button-loading'),
                success: ({ error, message }) => {
                    if (!error) {
                        $form[0].reset()
                        Theme.showSuccess(message)
                    } else {
                        Theme.showError(message)
                    }
                },
                error: (error) => Theme.handleError(error),
                complete: () => {
                    if (typeof refreshRecaptcha !== 'undefined') {
                        refreshRecaptcha()
                    }

                    $button.removeClass('button-loading')
                },
            })
        })
        .on('click', '.sticky-actions-button button', (e) => {
            e.preventDefault()

            const currentTarget = $(e.currentTarget)
            const form = $('form.product-form')

            if (currentTarget.prop('name') === 'add-to-cart') {
                form.find('button[type="submit"][name="add-to-cart"]').trigger('click')
            }

            if (currentTarget.prop('name') === 'checkout') {
                form.find('button[type="submit"][name="checkout"]').trigger('click')
            }
        })
        .on('click', '[data-bb-toggle="open-mini-cart"]', (e) => {
            $('[data-bb-toggle="mini-cart-content-slot"]').html('<div class="loading-spinner"></div>')

            $.ajax({
                url: $(e.currentTarget).data('url'),
                type: 'GET',
                success: ({ data }) => {
                    $('[data-bb-toggle="mini-cart-content-slot"]').html(data.content)
                    $('[data-bb-toggle="mini-cart-footer-slot"]').html(data.footer)

                    if (typeof Theme.lazyLoadInstance !== 'undefined') {
                        Theme.lazyLoadInstance.update()
                    }
                },
                error: (error) => Theme.handleError(error),
            })
        })

    document.addEventListener('ecommerce.quick-view.initialized', () => {
        $('[data-countdown]').countdown()
    })

    document.addEventListener('ecommerce.cart.added', (e) => {
        const { data } = e.detail

        loadAjaxCart(data)

        $('.cartmini__area').addClass('cartmini-opened')
        $('.body-overlay').addClass('opened')
    })

    document.addEventListener('ecommerce.cart.removed', (e) => {
        const { data } = e.detail
        if (data.count === 0) {
            $('.cartmini__area').removeClass('cartmini-opened')
            $('.body-overlay').removeClass('opened')
        }

        loadAjaxCart(data)
    })

    document.addEventListener('ecommerce.wishlist.removed', (e) => {
        const { data, element } = e.detail

        element.closest('tr').remove()

        if (data.count === 0) {
            window.location.reload()
        }

        loadAjaxCart(data)
    })

    document.addEventListener('ecommerce.compare.added', (e) => {
        const { element } = e.detail

        if (element.find('span')) {
            element
                .find('span')
                .text(
                    element.hasClass('active')
                        ? element.data('remove-text')
                        : element.data('add-text')
                )
        }
    })

    document.addEventListener('ecommerce.wishlist.added', (e) => {
        const { data, element } = e.detail

        data.added ? element.addClass('active') : element.removeClass('active')

        if (element.find('span')) {
            element
                .find('span')
                .text(data.added ? element.data('remove-text') : element.data('add-text'))
        }
    })

    document.addEventListener('ecommerce.compare.removed', (e) => {
        const { element } = e.detail

        if (element.find('span')) {
            element
                .find('span')
                .text(
                    element.hasClass('active')
                        ? element.data('remove-text')
                        : element.data('add-text')
                )
        }
    })

    document.addEventListener('ecommerce.product-filter.before', () => {
        $('.tp-shop-area > .container, .bb-shop-detail > .container > .bb-shop-tab-content').append(
            '<div class="loading-spinner"></div>'
        )
    })

    document.addEventListener('ecommerce.product-filter.success', (e) => {
        const { data } = e.detail

        $('.bb-product-items-wrapper').html(data.data)

        if (data.additional) {
            $('.bb-shop-sidebar').replaceWith(data.additional.filters_html)
        }

        $('.tp-shop-top-result p').text(data.message)

        if ($('.bb-product-price-filter').length) {
            EcommerceApp.initPriceFilter()
        }

        $('html, body').animate({
            scrollTop: $('.tp-shop-main-wrapper').offset().top - 120,
        })
    })

    document.addEventListener('ecommerce.product-filter.completed', () => {
        $('.tp-shop-area > .container, .bb-shop-detail > .container > .bb-shop-tab-content').find('.loading-spinner').remove()

        if (typeof Theme.lazyLoadInstance !== 'undefined') {
            Theme.lazyLoadInstance.update()
        }
    })

    document.addEventListener('ecommerce.quick-shop.before-send', (e) => {
        const { element, modal } = e.detail

        element.addClass('btn-loading')
        modal.find('.modal-body').css('min-height', '16rem').html('<div class="loading-spinner"></div>')
    })

    document.addEventListener('ecommerce.quick-shop.completed', (e) => {
        const { element, modal } = e.detail

        element.removeClass('btn-loading')
        modal.find('.modal-body').css('min-height', '0')
    })

    if (window.location.hash === '#product-review') {
        $(document).find('[data-bb-toggle="scroll-to-review"]').trigger('click')
    }

    const triggerProductTab = () => {
        $(document).find('[data-bb-toggle="product-tab"]').first().trigger('click')
    }

    triggerProductTab()

    document.addEventListener('shortcode.loaded', (e) => {
        const { name, attributes } = e.detail

        if (name === 'ecommerce-product-groups' && attributes.style !== 'columns') {
            triggerProductTab()
        }

        $('[data-countdown]').countdown()
    })
})
