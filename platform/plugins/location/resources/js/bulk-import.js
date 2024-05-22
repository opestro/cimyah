$(() => {
    $(document).on('submit', '[data-bb-toggle="import-available-data"]', async (e) => {
        e.preventDefault()

        const currentTarget = $(e.currentTarget)
        const countries = currentTarget.find('select').val()

        if (!countries || countries.length === 0) {
            Botble.showError(currentTarget.data('empty-selection-message'))
            return
        }

        for (const value of countries) {
            const isLast = value === countries[countries.length - 1]

            try {
                const formData = new FormData()
                formData.append('country_code', value)
                formData.append('continue', isLast ? 0 : 1)

                const { error, data } = await $httpClient
                    .make()
                    .withButtonLoading(currentTarget.find('button[type=submit]'))
                    .post(currentTarget.prop('action'), formData)

                if (error) {
                    Botble.showError(data.message)
                    return
                }

                if (isLast) {
                    Botble.showSuccess(data.message)
                    currentTarget.find('select').val('').trigger('change')
                }
            } catch (error) {
                Botble.showError(error.message)
            }
        }
    })
})
