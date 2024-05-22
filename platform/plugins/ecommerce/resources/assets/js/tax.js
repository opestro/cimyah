$(() => {
    const spinner = `<div class='w-100 text-center py-3'><div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div></div>`
    const table = 'ecommerce-tax-rule-table'
    const _table = '#' + table
    const $modal = $('.create-tax-rule-form-modal')
    const $modalBody = $modal.find('.modal-body')
    const $modalTitle = $modal.find('.modal-title strong')

    const resetModal = () => {
        $modalBody.html(spinner)
        $modalTitle.text('...')
    }

    const setModal = (res) => {
        $modalBody.html(res.data.html)
        $modalTitle.text(res.message || '...')
    }

    $modal.on('show.bs.modal', function () {
        resetModal()
    })

    $(document)
        .off('click', '.create-tax-rule-item')
        .on('click', '.create-tax-rule-item', function (e) {
            e.preventDefault()
            const $this = $(e.currentTarget)
            $modal.modal('show')

            $.ajax({
                url: $this.find('[data-action=create]').data('href'),
                success: (res) => {
                    if (res.error == false) {
                        setModal(res)
                        Botble.initResources()
                    } else {
                        Botble.showError(res.message)
                    }
                },
                error: (res) => {
                    Botble.handleError(res)
                },
            })
        })

    $(document).on('click', _table + ' .btn-edit-item', function (e) {
        e.preventDefault()
        const $this = $(e.currentTarget)
        $modal.modal('show')

        $.ajax({
            url: $this.prop('href'),
            success: (res) => {
                if (res.error == false) {
                    setModal(res)
                    Botble.initResources()
                } else {
                    Botble.showError(res.message)
                }
            },
            error: (res) => {
                Botble.handleError(res)
            },
        })
    })

    $(document).on('submit', '#ecommerce-tax-rule-form', function (e) {
        e.preventDefault()
        const $this = $(e.currentTarget)

        $.ajax({
            url: $this.prop('action'),
            method: 'POST',
            data: $this.serializeArray(),
            success: (res) => {
                if (res.error == false) {
                    if (window.LaravelDataTables && window.LaravelDataTables[table]) {
                        LaravelDataTables[table].draw()
                    }
                    $modal.modal('hide')
                } else {
                    Botble.showError(res.message)
                }
            },
            error: (res) => {
                Botble.handleError(res)
            },
        })
    })
})
