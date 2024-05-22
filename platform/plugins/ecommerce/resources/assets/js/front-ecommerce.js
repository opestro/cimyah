class Ecommerce {
    quickSearchAjax = null

    constructor() {
        $(document)
            .on('click', '[data-bb-toggle="toggle-product-categories-tree"]', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)

                currentTarget.toggleClass('active')
                currentTarget.closest('.bb-product-filter-item').find('> .bb-product-filter-items').slideToggle()
            })
            .on('click', '[data-bb-toggle="toggle-filter-sidebar"]', () => {
                $('.bb-filter-offcanvas-area').toggleClass('offcanvas-opened')
                $('.body-overlay').toggleClass('opened')
            })
            .on('click', '.body-overlay', () => {
                $('.bb-filter-offcanvas-area').removeClass('offcanvas-opened')
                $('.body-overlay').removeClass('opened')
            })
            .on('submit', 'form.bb-product-form-filter', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)

                const formData = this.#transformFormData(currentTarget.serializeArray())
                const url = currentTarget.prop('action')
                let nextUrl = url
                let params = []

                formData.map((item) => {
                    params.push(`${encodeURIComponent(item.name)}=${encodeURIComponent(item.value)}`)
                })

                if (params.length) {
                    nextUrl += `?${params.join('&')}`
                }

                formData.push({ name: '_', value: Date.now() })

                if (window.location.href === nextUrl) {
                    return
                }

                this.#ajaxFilterForm(url, formData, nextUrl)
            })
            .on('change', 'form.bb-product-form-filter input, form.bb-product-form-filter select', (e) => {
                $(e.currentTarget).closest('form').trigger('submit')
            })
            .on('keyup', '.bb-form-quick-search input', (e) => {
                this.#ajaxSearchProducts($(e.currentTarget).closest('form'))
            })
            .on('click', 'body', (e) => {
                if (!$(e.target).closest('.bb-form-quick-s4earch').length) {
                    $('.bb-quick-search-results').removeClass('show').html('')
                }
            })
            .on('click', '[data-bb-toggle="quick-shop"]', (e) => {
                const currentTarget = $(e.currentTarget)
                const modal = $('#quick-shop-modal')

                $.ajax({
                    url: currentTarget.data('url'),
                    type: 'GET',
                    beforeSend: () => {
                        modal.find('.modal-body').html('')
                        modal.modal('show')

                        document.dispatchEvent(
                            new CustomEvent('ecommerce.quick-shop.before-send', {
                                detail: {
                                    element: currentTarget,
                                    modal,
                                },
                            })
                        )
                    },
                    success: ({ data }) => {
                        modal.find('.modal-body').html(data)
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => {
                        document.dispatchEvent(
                            new CustomEvent('ecommerce.quick-shop.completed', {
                                detail: {
                                    element: currentTarget,
                                    modal,
                                },
                            })
                        )
                    },
                })
            })
            .on('click', '.bb-product-filter-link', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)
                const form = currentTarget.closest('form')
                const parent = currentTarget.closest('.bb-product-filter')
                const input = form.find('input[name="categories[]"]')

                parent.find('.bb-product-filter-link').removeClass('active')
                currentTarget.addClass('active')

                if (input.length) {
                    input.val(currentTarget.data('id')).trigger('change')
                } else {
                    form.prop('action', currentTarget.prop('href')).trigger('submit')
                }
            })
            .on('click', '.bb-product-filter-clear', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)

                this.#ajaxFilterForm(currentTarget.prop('href'))
            })
            .on('click', '.bb-product-filter-clear-all', (e) => {
                e.preventDefault()

                const form = $('.bb-product-form-filter')

                form.find(
                    'input[type="text"], input[type="hidden"], input[type="checkbox"], input[type="radio"], select'
                ).val(null)

                form.trigger('submit')
            })
            .on('submit', 'form#cancel-order-form', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)
                const modal = currentTarget.closest('.modal')
                const button = modal.find('button[type="submit"]')

                $.ajax({
                    url: currentTarget.prop('action'),
                    type: 'POST',
                    data: currentTarget.serialize(),
                    beforeSend: () => {
                        button.addClass('btn-loading')
                    },
                    success: ({ error, message }) => {
                        if (error) {
                            Theme.showError(message)

                            return
                        }

                        Theme.showSuccess(message)

                        modal.modal('hide')

                        setTimeout(() => window.location.reload(), 1000)
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => button.removeClass('btn-loading'),
                })
            })
            .on('click', '[data-bb-toggle="add-to-compare"]', function (e) {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)

                const url = currentTarget.hasClass('active')
                    ? currentTarget.data('remove-url')
                    : currentTarget.data('url')
                let data = {}

                if (currentTarget.hasClass('active')) {
                    data = { _method: 'DELETE' }
                }

                $.ajax({
                    url,
                    method: 'POST',
                    data,
                    beforeSend: () => currentTarget.addClass('btn-loading'),
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)
                        } else {
                            Theme.showSuccess(message)
                            currentTarget.toggleClass('active')

                            if (data.count !== undefined) {
                                $('[data-bb-value="compare-count"]').text(data.count)
                            }

                            if (currentTarget.hasClass('active')) {
                                document.dispatchEvent(
                                    new CustomEvent('ecommerce.compare.added', {
                                        detail: { data, element: currentTarget },
                                    })
                                )
                            } else {
                                document.dispatchEvent(
                                    new CustomEvent('ecommerce.compare.removed', {
                                        detail: { data, element: currentTarget },
                                    })
                                )
                            }
                        }
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => currentTarget.removeClass('btn-loading'),
                })
            })
            .on('click', '[data-bb-toggle="remove-from-compare"]', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)
                const table = currentTarget.closest('table')

                $.ajax({
                    url: currentTarget.data('url'),
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                    },
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)
                        } else {
                            Theme.showSuccess(message)

                            document.dispatchEvent(
                                new CustomEvent('ecommerce.compare.removed', {
                                    detail: { data, element: currentTarget },
                                })
                            )

                            if (data.count !== undefined) {
                                $('[data-bb-value="compare-count"]').text(data.count)
                            }

                            if (data.count > 0) {
                                table.find(`td:nth-child(${currentTarget.closest('td').index() + 1})`).remove()
                            } else {
                                window.location.reload()
                            }
                        }
                    },
                    error: (error) => Theme.handleError(error),
                })
            })
            .on('click', '[data-bb-toggle="add-to-wishlist"]', function (e) {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)

                const url = currentTarget.data('url')

                $.ajax({
                    url,
                    method: 'POST',
                    beforeSend: () => currentTarget.addClass('btn-loading'),
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)
                        } else {
                            if (data.count !== undefined) {
                                $('[data-bb-value="wishlist-count"]').text(data.count)
                            }

                            Theme.showSuccess(message)

                            document.dispatchEvent(
                                new CustomEvent('ecommerce.wishlist.added', {
                                    detail: { data, element: currentTarget },
                                })
                            )
                        }
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => currentTarget.removeClass('btn-loading'),
                })
            })
            .on('click', '[data-bb-toggle="remove-from-wishlist"]', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)

                $.ajax({
                    url: currentTarget.data('url'),
                    method: 'POST',
                    data: { _method: 'DELETE' },
                    beforeSend: () => currentTarget.addClass('btn-loading'),
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)
                        } else {
                            Theme.showSuccess(message)

                            if (data.count !== undefined) {
                                $('[data-bb-value="wishlist-count"]').text(data.count)
                            }

                            currentTarget.closest('tr').remove()

                            if (data.count === 0) {
                                window.location.reload()
                            }

                            document.dispatchEvent(
                                new CustomEvent('ecommerce.wishlist.removed', {
                                    detail: { data, element: currentTarget },
                                })
                            )
                        }
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => currentTarget.removeClass('btn-loading'),
                })
            })
            .on('click', '[data-bb-toggle="add-to-cart"]', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)
                const data = {
                    id: currentTarget.data('id'),
                }

                const quantity = currentTarget.closest('tr').find('input[name="qty"]')

                if (quantity) {
                    data.qty = quantity.val()
                }

                $.ajax({
                    url: currentTarget.data('url'),
                    method: 'POST',
                    data: data,
                    dataType: 'json',
                    beforeSend: () => currentTarget.addClass('btn-loading'),
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)
                        } else {
                            Theme.showSuccess(message)

                            if (data.count !== undefined) {
                                $('[data-bb-value="cart-count"]').text(data.count)
                            }

                            document.dispatchEvent(
                                new CustomEvent('ecommerce.cart.added', {
                                    detail: { data, element: currentTarget },
                                })
                            )
                        }
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => currentTarget.removeClass('btn-loading'),
                })
            })
            .on('click', '[data-bb-toggle="remove-from-cart"]', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)

                $.ajax({
                    url: currentTarget.prop('href') || currentTarget.data('url'),
                    method: 'GET',
                    beforeSend: () => currentTarget.addClass('btn-loading'),
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)
                        } else {
                            Theme.showSuccess(message)

                            currentTarget.closest('tr').remove()

                            if (data.count !== undefined) {
                                $('[data-bb-value="cart-count"]').text(data.count)
                            }

                            if (data.count === 0) {
                                window.location.reload()
                            }

                            document.dispatchEvent(
                                new CustomEvent('ecommerce.cart.removed', {
                                    detail: { data, element: currentTarget },
                                })
                            )
                        }
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => currentTarget.removeClass('btn-loading'),
                })
            })
            .on('submit', '[data-bb-toggle="coupon-form"]', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)
                const button = currentTarget.find('button[type="submit"]')

                $.ajax({
                    url: currentTarget.prop('action'),
                    type: 'POST',
                    data: currentTarget.serialize(),
                    beforeSend: () => button.prop('disabled', true).addClass('btn-loading'),
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)
                        } else {
                            Theme.showSuccess(message)

                            document.dispatchEvent(
                                new CustomEvent('ecommerce.coupon.applied', {
                                    detail: { data, element: currentTarget },
                                })
                            )
                        }
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => button.prop('disabled', false).removeClass('btn-loading'),
                })
            })
            .on('click', '[data-bb-toggle="quick-view-product"]', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)

                $.ajax({
                    url: currentTarget.data('url'),
                    type: 'GET',
                    beforeSend: () => currentTarget.prop('disabled', true).addClass('btn-loading'),
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)
                        } else {
                            const quickViewModal = $('[data-bb-toggle="quick-view-modal"]')
                            quickViewModal.modal('show')
                            quickViewModal.find('.modal-body').html(data)

                            document.dispatchEvent(
                                new CustomEvent('ecommerce.quick-view.initialized', {
                                    detail: { data, element: currentTarget },
                                })
                            )

                            setTimeout(() => { this.initProductGallery(true) }, 100)
                        }
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => currentTarget.prop('disabled', false).removeClass('btn-loading'),
                })
            })
            .on('click', '[data-bb-toggle="product-form"] button[type="submit"]', (e) => {
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

                            return
                        }
                        Theme.showSuccess(message)

                        form.find('input[name="qty"]').val(1)

                        if (data.count !== undefined) {
                            $('[data-bb-value="cart-count"]').text(data.count)
                        }

                        document.dispatchEvent(
                            new CustomEvent('ecommerce.cart.added', {
                                detail: { data, element: currentTarget },
                            })
                        )
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => currentTarget.prop('disabled', false).removeClass('btn-loading'),
                })
            })

        if ($('.bb-product-price-filter').length) {
            this.initPriceFilter()
        }

        this.#initCategoriesDropdown()
    }

    /**
     * @returns {boolean}
     */
    isRtl() {
        return document.body.getAttribute('dir') === 'rtl'
    }

    /**
     * @param {JQuery} element
     */
    initLightGallery(element) {
        if (!element.length) {
            return
        }

        if (element.data('lightGallery')) {
            element.data('lightGallery').destroy(true)
        }

        element.lightGallery({
            selector: 'a',
            thumbnail: true,
            share: false,
            fullScreen: false,
            autoplay: false,
            autoplayControls: false,
            actualSize: false,
        })
    }

    initProductGallery(onlyQuickView = false) {

        if (!onlyQuickView) {
            const $gallery = $('.bb-product-gallery-images')

            if (!$gallery.length) {
                return
            }

            const $thumbnails = $('.bb-product-gallery-thumbnails')

            if ($gallery.length) {
                $gallery.map((index, item) => {
                    const $item = $(item)
                    if ($item.hasClass('slick-initialized')) {
                        $item.slick('unslick')
                    }

                    $item.slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                        dots: false,
                        infinite: false,
                        fade: true,
                        lazyLoad: 'ondemand',
                        asNavFor: '.bb-product-gallery-thumbnails',
                        rtl: this.isRtl(),
                    })
                })
            }

            if ($thumbnails.length) {
                $thumbnails.slick({
                    slidesToShow: 6,
                    slidesToScroll: 1,
                    asNavFor: '.bb-product-gallery-images',
                    focusOnSelect: true,
                    infinite: false,
                    rtl: this.isRtl(),
                    vertical: $thumbnails.data('vertical') === 1,
                    prevArrow:
                        '<button class="slick-prev slick-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg></button>',
                    nextArrow:
                        '<button class="slick-next slick-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg></button>',
                    responsive: [
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 4,
                                vertical: false,
                            },
                        },
                    ],
                })
            }

            this.initLightGallery($gallery)

            if (typeof Theme.lazyLoadInstance !== 'undefined') {
                Theme.lazyLoadInstance.update()
            }
        }

        const $quickViewGallery = $(document).find('.bb-quick-view-gallery-images')

        if ($quickViewGallery.length) {
            if ($quickViewGallery.hasClass('slick-initialized')) {
                $quickViewGallery.slick('unslick')
            }

            $quickViewGallery.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: false,
                arrows: true,
                adaptiveHeight: false,
                rtl: this.isRtl(),
            })
        }

        this.initLightGallery($quickViewGallery)
    }

    initPriceFilter() {
        if (typeof $.fn.slider === 'undefined') {
            throw new Error('jQuery UI slider is required for price filter')
        }

        const $priceFilter = $('.bb-product-price-filter')
        const $sliderRange = $priceFilter.find('.price-slider')
        const $rangeLabel = $priceFilter.find('.input-range-label')

        if ($priceFilter) {
            const $minPrice = $priceFilter.find('input[name="min_price"]')
            const $maxPrice = $priceFilter.find('input[name="max_price"]')

            $sliderRange.slider({
                range: true,
                min: $sliderRange.data('min'),
                max: $sliderRange.data('max'),
                values: [$minPrice.val(), $maxPrice.val()],
                slide: function (event, ui) {
                    $rangeLabel.find('.from').text(EcommerceApp.formatPrice(ui.values[0]))
                    $rangeLabel.find('.to').text(EcommerceApp.formatPrice(ui.values[1]))
                },
                change: function (event, ui) {
                    if (parseInt($minPrice.val()) !== ui.values[0]) {
                        $minPrice.val(ui.values[0]).trigger('change')
                    }

                    if (parseInt($maxPrice.val()) !== ui.values[1]) {
                        $maxPrice.val(ui.values[1]).trigger('change')
                    }
                },
            })

            $rangeLabel.find('.from').text(this.formatPrice($sliderRange.slider('values', 0)))
            $rangeLabel.find('.to').text(this.formatPrice($sliderRange.slider('values', 1)))
        }
    }

    formatPrice(price, numberAfterDot, x) {
        const currencies = window.currencies || {}

        if (!numberAfterDot) {
            numberAfterDot = currencies.number_after_dot !== undefined ? currencies.number_after_dot : 2
        }

        const regex = '\\d(?=(\\d{' + (x || 3) + '})+$)'
        let priceUnit = ''

        if (currencies.show_symbol_or_title) {
            priceUnit = currencies.symbol || currencies.title || ''
        }

        if (currencies.display_big_money) {
            let label = ''

            if (price >= 1000000 && price < 1000000000) {
                price = price / 1000000
                label = currencies.million
            } else if (price >= 1000000000) {
                price = price / 1000000000
                label = currencies.billion
            }

            priceUnit = label + (priceUnit ? ` ${priceUnit}` : '')
        }

        price = price.toFixed(Math.max(0, ~~numberAfterDot)).toString().split('.')

        price =
            price[0].toString().replace(new RegExp(regex, 'g'), `$&${currencies.thousands_separator}`) +
            (price[1] ? currencies.decimal_separator + price[1] : '')

        if (currencies.show_symbol_or_title) {
            price = currencies.is_prefix_symbol ? priceUnit + price : price + priceUnit
        }

        return price
    }

    #transformFormData = (formData) => {
        let data = []

        formData.map((item) => {
            if (item.value) {
                data.push(item)
            }
        })

        return data
    }

    #ajaxSearchProducts = (form, url) => {
        const button = form.find('button[type="submit"]')
        const input = form.find('input[name="q"]')
        const results = form.find('.bb-quick-search-results')

        if (!input.val()) {
            results.removeClass('show').html('')

            return
        }

        this.quickSearchAjax = $.ajax({
            type: 'GET',
            url: url || form.data('ajax-url'),
            data: form.serialize(),
            beforeSend: () => {
                button.addClass('btn-loading')

                if (!url) {
                    results.removeClass('show').html('')
                }

                if (this.quickSearchAjax !== null) {
                    this.quickSearchAjax.abort()
                }
            },
            success: ({ error, message, data }) => {
                if (error) {
                    Theme.showError(message)

                    return
                }

                results.addClass('show')

                if (url) {
                    results.find('.bb-quick-search-list').append($(data).find('.bb-quick-search-list').html())
                } else {
                    results.html(data)
                }
            },
            complete: () => button.removeClass('btn-loading'),
        })
    }

    #ajaxFilterForm = (url, data, nextUrl) => {
        const form = $('.bb-product-form-filter')

        $.ajax({
            url: url,
            type: 'GET',
            data: data,
            beforeSend: () => {
                document.dispatchEvent(
                    new CustomEvent('ecommerce.product-filter.before', {
                        detail: {
                            data: data,
                            element: form,
                        },
                    })
                )
            },
            success: (data) => {
                const { message, error } = data

                if (error) {
                    Theme.showError(message)

                    return
                }

                window.history.pushState(data, null, nextUrl || url)

                document.dispatchEvent(
                    new CustomEvent('ecommerce.product-filter.success', {
                        detail: {
                            data,
                            element: form,
                        },
                    })
                )
            },
            error: (error) => Theme.handleError(error),
            complete: () => {
                if (typeof Theme.lazyLoadInstance !== 'undefined') {
                    Theme.lazyLoadInstance.update()
                }

                document.dispatchEvent(
                    new CustomEvent('ecommerce.product-filter.completed', {
                        detail: {
                            element: form,
                        },
                    })
                )
            },
        })
    }

    #initCategoriesDropdown = async () => {
        const makeRequest = (url, beforeCallback, successCallback) => {
            $.ajax({
                url,
                method: 'GET',
                beforeSend: () => beforeCallback(),
                success: ({ error, data }) => {
                    if (error) {
                        return
                    }

                    successCallback(data)

                    document.dispatchEvent(
                        new CustomEvent('ecommerce.categories-dropdown.success', {
                            detail: {
                                data,
                            },
                        })
                    )
                },
                error: (error) => Theme.handleError(error),
            })
        }

        const initCategoriesDropdown = $(document).find('[data-bb-toggle="init-categories-dropdown"]')

        if (initCategoriesDropdown.length) {
            const url = initCategoriesDropdown.first().data('url')

            makeRequest(
                url,
                () => {},
                (data) => {
                    initCategoriesDropdown.each((index, element) => {
                        const currentTarget = $(element)
                        const target = $(currentTarget.data('bb-target'))

                        if (target.length) {
                            target.html(data.dropdown)
                        } else {
                            currentTarget.append(data.select)
                        }
                    })
                }
            )
        }
    }

    productQuantityToggle = () => {
        const $container = $('[data-bb-toggle="product-quantity"]')

        $container.on('click', '[data-bb-toggle="product-quantity-toggle"]', function (e) {
            const $currentTarget = $(e.currentTarget)

            let $calculation = $currentTarget.data('value')

            if (!$calculation) {
                return
            }

            let $input = null

            if ($calculation === 'plus') {
                $input = $currentTarget.prev()
            } else if ($calculation === 'minus') {
                $input = $currentTarget.next()
            }

            if (!$input) {
                return
            }

            let $quantity = parseInt($input.val()) || 1

            $input.val($calculation === 'plus' ? $quantity + 1 : $quantity === 1 ? 1 : $quantity - 1)

            document.dispatchEvent(
                new CustomEvent('ecommerce.cart.quantity.change', {
                    detail: {
                        element: $currentTarget,
                        action: $calculation === '+' ? 'increase' : 'decrease',
                    },
                })
            )
        })
    }

    onChangeProductAttribute = () => {
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

            const $quickViewGalleryImages = $(document).find('.bb-quick-view-gallery-images')

            if ($quickViewGalleryImages.length) {
                $quickViewGalleryImages.slick('unslick').html(imageHtml)
            }

            $product.find('.bb-product-gallery-images').slick('unslick').html(imageHtml)

            if (typeof EcommerceApp !== 'undefined') {
                EcommerceApp.initProductGallery()
            }
        }
    }

    handleUpdateCart = (element) => {
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

                this.ajaxLoadCart(data)
            },
            error: (error) => Theme.handleError(error),
        })
    }

    ajaxLoadCart = (data) => {
        if (!data) {
            return
        }

        const $cart = $('[data-bb-toggle="cart-content"]')

        if (data.count !== undefined) {
            $('[data-bb-value="cart-count"]').text(data.count)
        }

        if (data.total_price !== undefined) {
            $('[data-bb-value="cart-total-price"]').text(data.total_price)
        }

        if ($cart.length) {
            $cart.replaceWith(data.cart_content)
            this.productQuantityToggle()

            if (typeof Theme.lazyLoadInstance !== 'undefined') {
                Theme.lazyLoadInstance.update()
            }
        }
    }
}

$(() => {
    window.EcommerceApp = new Ecommerce()

    EcommerceApp.productQuantityToggle()

    EcommerceApp.initProductGallery()

    EcommerceApp.onChangeProductAttribute()

    if ($('.bb-product-price-filter').length) {
        EcommerceApp.initPriceFilter()
    }

    document.addEventListener('ecommerce.quick-shop.completed', () => {
        EcommerceApp.productQuantityToggle()
    })

    document.addEventListener('ecommerce.cart.quantity.change', (e) => {
        const { element } = e.detail
        EcommerceApp.handleUpdateCart(element)
    })

    document.addEventListener('ecommerce.product-filter.before', () => {
        $('[data-bb-toggle="product-list"]')
            .find('.bb-product-items-wrapper')
            .append('<div class="loading-spinner"></div>')
    })

    document.addEventListener('ecommerce.product-filter.success', (e) => {
        const { data } = e.detail

        $('.bb-product-items-wrapper').html(data.data)

        if (data.additional) {
            $('.bb-shop-sidebar').replaceWith(data.additional.filters_html)
        }

        if ($('.bb-product-price-filter').length) {
            EcommerceApp.initPriceFilter()
        }

        $('html, body').animate({
            scrollTop: $('.bb-product-items-wrapper').offset().top - 120,
        })
    })

    document.addEventListener('ecommerce.product-filter.completed', () => {
        if (typeof Theme.lazyLoadInstance !== 'undefined') {
            Theme.lazyLoadInstance.update()
        }
    })
})
