$(() => {
    const $newsletterPopup = $('#newsletter-popup')

    const dontShowAgain = (time) => {
        const date = new Date()
        date.setTime(date.getTime() + time)
        document.cookie = `newsletter_popup=1; expires=${date.toUTCString()}; path=/`
    }

    if ($newsletterPopup.length > 0) {
        if (document.cookie.indexOf('newsletter_popup=1') === -1) {
            setTimeout(() => {
                $newsletterPopup.modal('show')
            }, $newsletterPopup.data('delay') * 1000)
        }

        $newsletterPopup
            .on('show.bs.modal', () => {
                const dialog = $newsletterPopup.find('.modal-dialog')

                dialog.css('margin-top', (Math.max(0, ($(window).height() - dialog.height()) / 2) / 2))
            })
            .on('hide.bs.modal', () => {
                const checkbox = $newsletterPopup.find('form').find('input[name="dont_show_again"]')

                if (checkbox.is(':checked')) {
                    dontShowAgain(3 * 24 * 60 * 60 * 1000) // 1 day
                } else {
                    dontShowAgain(60 * 60 * 1000) // 1 hour
                }
            })

        document.addEventListener('newsletter.subscribed', () => dontShowAgain())
    }
})
