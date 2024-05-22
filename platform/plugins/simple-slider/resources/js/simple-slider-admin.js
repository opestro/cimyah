class SimpleSliderAdminManagement {
    init(tableId) {
        const $table = $(document).find(`#${tableId}_wrapper`)

        $.each($table.find('tbody'), (index, el) => {
            Sortable.create(el, {
                group: el + '_' + index, // or { name: "...", pull: [true, false, clone], put: [true, false, array] }
                sort: true, // sorting inside list
                delay: 0, // time in milliseconds to define when the sorting should start
                disabled: false, // Disables the sortable if set to true.
                store: null, // @see Store
                animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
                handle: 'tr',
                ghostClass: 'sortable-ghost', // Class name for the drop placeholder
                chosenClass: 'sortable-chosen', // Class name for the chosen item
                dataIdAttr: 'data-id',

                forceFallback: false, // ignore the HTML5 DnD behaviour and force the fallback to kick in
                fallbackClass: 'sortable-fallback', // Class name for the cloned DOM Element when using forceFallback
                fallbackOnBody: false, // Appends the cloned DOM Element into the Document's Body

                scroll: true, // or HTMLElement
                scrollSensitivity: 30, // px, how near the mouse must be to an edge to start scrolling.
                scrollSpeed: 10, // px

                // dragging ended
                onEnd: () => {
                    const $box = $(el).closest('.card')
                    $box.find('.btn-save-sort-order').addClass('sort-button-active').show()
                    $.each($box.find('tbody tr'), (index, sort) => {
                        $(sort)
                            .find('.order-column')
                            .text(index + 1)
                    })
                },
            })
        })

        const $sortButton = $table.closest('.card').find('.btn-save-sort-order')

        $sortButton.off('click').on('click', (event) => {
            event.preventDefault()
            const _self = $(event.currentTarget)

            let items = []
            $.each(_self.closest('.card').find('tbody tr'), (index, sort) => {
                items.push(parseInt($(sort).find('td:first-child').text()))
                $(sort)
                    .find('.order-column')
                    .text(index + 1)
            })

            Botble.showButtonLoading(_self)

            $httpClient
                .make()
                .post($sortButton.data('url'), {
                    items,
                })
                .then(({ data }) => {
                    Botble.showSuccess(data.message)
                })
                .finally(() => {
                    Botble.hideButtonLoading(_self)
                    _self.hide()
                })
        })
    }
}

$(() => {
    document.addEventListener('core-table-init-completed', function (event) {
        new SimpleSliderAdminManagement().init(event.detail.table.prop('id'))
    })

    $(document)
        .on('show.bs.modal', '#simple-slider-item-modal', (e) => {
            const modal = $(e.currentTarget)
            const href = $(e.relatedTarget).prop('href')

            $httpClient
                .make()
                .withLoading(modal.find('.modal-content'))
                .get(href)
                .then(({ data }) => {
                    modal.find('.modal-header .modal-title').text(data.data.title)
                    modal.find('.modal-body').html(data.data.content)

                    Botble.initMediaIntegrate()

                    Botble.initResources()
                })
        })

        .on('click', '#simple-slider-item-modal button[type="submit"]', (e) => {
            e.preventDefault()

            const button = $(e.currentTarget)
            const modal = button.closest('.modal')
            const form = modal.find('form')

            $httpClient
                .make()
                .withLoading(form)
                .withButtonLoading(button)
                .post(form.prop('action'), form.serialize())
                .then(({ data }) => {
                    Botble.showSuccess(data.message)

                    modal.modal('hide')

                    $('#botble-simple-slider-tables-simple-slider-item-table').DataTable().draw()
                })
        })
})
