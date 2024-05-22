'use strict'

$(() => {
    $(document).on('change', '.shortcode-tabs-quantity-select', function () {
        const $this = $(this)
        const quantity = parseInt($this.val()) || 1

        $this.val(quantity)

        const $section = $this.closest('.shortcode-admin-config')

        for (let index = 1; index <= $this.data('max'); index++) {
            const $el = $section.find('[data-tab-id=' + index + ']')
            if (index <= quantity) {
                if ($el.hasClass('d-none')) {
                    $el.removeClass('d-none')
                    $el.find('[data-name]').map(function (i, e) {
                        $(e).prop('name', $(e).data('name'))
                    })
                }
            } else {
                $el.addClass('d-none')
                $el.find('[name]').map(function (i, e) {
                    $(e).data('name', $(e).prop('name'))
                    $(e).removeProp('name')
                })
            }
        }
    })
})
