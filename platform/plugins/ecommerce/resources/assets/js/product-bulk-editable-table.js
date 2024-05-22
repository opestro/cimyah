'use strict'
;(function ($) {
    $(document).on('change', '[data-bb-toggle="product-bulk-change"]', function () {
        const _self = $(this)
        const tableElement = _self.closest('table')
        const id = _self.data('id')
        const value = _self.is(':checkbox') || _self.is(':radio') ? (_self.is(':checked') ? '1' : '0') : _self.val()
        const column = _self.data('column')
        const targetElements = $(`[data-target-id="${id}"]`)

        if (targetElements.length > 0) {
            targetElements.hide()

            targetElements.each(function () {
                const _this = $(this)
                const targetValue = _this.data('target-value').toString()

                if (value === targetValue) {
                    _this.show()
                }
            })
        }

        $httpClient
            .make()
            .withLoading(tableElement[0])
            .put(_self.data('url'), {
                value: value,
                column: column,
            })
            .then(({ data }) => {
                Botble.showSuccess(data.message)
            })
    })
})(jQuery)
