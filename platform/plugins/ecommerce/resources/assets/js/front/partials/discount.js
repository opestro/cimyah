export class DiscountManagement {
    init() {
        $(document).on('click', '.btn-open-coupon-form', (event) => {
            event.preventDefault()
            $(document).find('.coupon-wrapper').toggle()
        })

        $('.coupon-wrapper .coupon-code').keypress((event) => {
            if (event.keyCode === 13) {
                $('.apply-coupon-code').trigger('click')
                event.preventDefault()
                event.stopPropagation()
                return false
            }
        })

        let target = '#main-checkout-product-info'

        $(document).on('click', '.apply-coupon-code', (event) => {
            event.preventDefault()

            const currentTarget = $(event.currentTarget)

            $.ajax({
                url: currentTarget.data('url'),
                type: 'POST',
                data: {
                    coupon_code: currentTarget.closest('.coupon-wrapper').find('.coupon-code').val(),
                    token: $('#checkout-token').val(),
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                beforeSend: () => {
                    currentTarget.find('i').remove()
                    currentTarget.html(`<i class="fa fa-spin fa-spinner"></i> ${currentTarget.html()}`)
                },
                success: ({ error, message }) => {
                    if (!error) {
                        $(target).load(`${window.location.href}?applied_coupon=1 ${target} > *`, () => currentTarget.find('i').remove())
                    } else {
                        $('.coupon-error-msg .text-danger').text(message)
                        currentTarget.find('i').remove()
                    }

                    $('html, body').animate({
                        scrollTop: $('.coupon-wrapper').offset().top
                    });
                },
                error: (data) => {
                    if (typeof data.responseJSON !== 'undefined') {
                        if (data.responseJSON.errors !== 'undefined') {
                            $.each(data.responseJSON.errors, (index, el) => {
                                $.each(el, (key, item) => {
                                    $('.coupon-error-msg .text-danger').text(item)
                                })
                            })
                        } else if (typeof data.responseJSON.message !== 'undefined') {
                            $('.coupon-error-msg .text-danger').text(data.responseJSON.message)
                        }
                    } else {
                        $('.coupon-error-msg .text-danger').text(data.status.text)
                    }
                    currentTarget.find('i').remove()
                },
            })
        })

        $(document).on('click', '.remove-coupon-code', (event) => {
            event.preventDefault()
            let _self = $(event.currentTarget)
            _self.find('i').remove()
            _self.html('<i class="fa fa-spin fa-spinner"></i> ' + _self.html())

            $.ajax({
                url: _self.data('url'),
                type: 'POST',
                data: {
                    token: $('#checkout-token').val(),
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: (res) => {
                    if (!res.error) {
                        $(target).load(window.location.href + ' ' + target + ' > *', function () {
                            _self.find('i').remove()
                        })
                    } else {
                        $('.coupon-error-msg .text-danger').text(res.message)
                        _self.find('i').remove()
                    }
                },
                error: (data) => {
                    if (typeof data.responseJSON !== 'undefined') {
                        if (data.responseJSON.errors !== 'undefined') {
                            $.each(data.responseJSON.errors, (index, el) => {
                                $.each(el, (key, item) => {
                                    $('.coupon-error-msg .text-danger').text(item)
                                })
                            })
                        } else if (typeof data.responseJSON.message !== 'undefined') {
                            $('.coupon-error-msg .text-danger').text(data.responseJSON.message)
                        }
                    } else {
                        $('.coupon-error-msg .text-danger').text(data.status.text)
                    }
                    _self.find('i').remove()
                },
            })
        })


        $(document).on('click', '[data-bb-toggle="apply-coupon-code"]', async function (e) {
            e.preventDefault();

            $(this).find('i').remove();
            $(this).html(`<i class="fa fa-spin fa-spinner"></i> ${$(this).html()}`);

            if ($(document).find('.remove-coupon-code').length) {
                $(document).find('.remove-coupon-code').trigger('click');
            }

            const discountCode = $(this).data('discount-code');

            $(document).find('.coupon-wrapper').show();
            $('.coupon-wrapper .coupon-code').val(discountCode);

            $('.apply-coupon-code').trigger('click');

            $(this).find('i').remove();
        });
    }
}
