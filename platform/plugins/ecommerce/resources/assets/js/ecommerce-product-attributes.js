class EcommerceProductAttribute {
    constructor() {
        this.template = $('#product_attribute_template').html()
        this.totalItem = $('.swatches-container .swatches-list tr').length
        this.deletedItems = []

        this.handleForm()
    }

    addNewAttribute() {
        let _self = this

        let template = _self.template
            .replace(/__id__/gi, 0)
            .replace(/__position__/gi, 0)
            .replace(/__checked__/gi, _self.totalItem == 0 ? 'checked' : '')
            .replace(/__title__/gi, '')
            .replace(/__color__/gi, '')
            .replace(/__image__/gi, '')

        $('.swatches-container .swatches-list').append(template)

        _self.totalItem++

        Botble.initMediaIntegrate()
    }

    exportData() {
        let data = []

        $('.swatches-container .swatches-list tr').each((index, item) => {
            let $current = $(item)

            data.push({
                id: $current.data('id'),
                is_default: $current.find('input[type=radio]').is(':checked') ? 1 : 0,
                order: $current.index(),
                title: $current.find('input[name="swatch-title"]').val(),
                color: $current.find('input[name="swatch-value"]').val(),
                image: $current.find('input[name="swatch-image"]').val(),
            })
        })

        return data
    }

    handleForm() {
        let _self = this

        $('.swatches-container .swatches-list').sortable()

        $('body')
            .on('submit', '.update-attribute-set-form', () => {
                let data = _self.exportData()

                $('#attributes').val(JSON.stringify(data))

                $('#deleted_attributes').val(JSON.stringify(_self.deletedItems))
            })
            .on('click', '.js-add-new-attribute', (event) => {
                event.preventDefault()

                _self.addNewAttribute()

                Botble.initColorPicker()
            })
            .on('click', '.swatches-container .swatches-list tr .remove-item', (event) => {
                event.preventDefault()

                const $item = $(event.currentTarget).closest('tr')

                _self.deletedItems.push($item.data('id'))

                $item.fadeOut('fast', () => $item.remove())
            })
    }
}

$(window).on('load', () => {
    new EcommerceProductAttribute()
})
