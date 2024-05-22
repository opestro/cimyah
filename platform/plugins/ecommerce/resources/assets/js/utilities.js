$(() => {
    if ($.fn.datepicker) {
        const $datePicker = $('#date_of_birth')

        console.log($datePicker.data('date-format'))

        $datePicker.datepicker({
            format: $datePicker.data('date-format') || 'yyyy-mm-dd',
            orientation: 'bottom',
        })
    }

    $('#avatar').on('change', (event) => {
        let input = event.currentTarget
        if (input.files && input.files[0]) {
            let reader = new FileReader()
            reader.onload = (e) => {
                $('.userpic-avatar').attr('src', e.target.result)
            }

            reader.readAsDataURL(input.files[0])
        }
    })
})
