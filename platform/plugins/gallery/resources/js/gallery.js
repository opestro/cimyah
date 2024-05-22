'use strict'

class GalleryManagement {
    init() {
        let container = document.getElementById('list-photo')

        if (container) {
            imagesLoaded(container, () => {
                new Masonry(container, {
                    isOriginLeft: $('body').prop('dir') !== 'rtl',
                })
            })

            if (jQuery().lightGallery) {
                $(container).lightGallery({
                    loop: true,
                    thumbnail: true,
                    fourceAutoply: false,
                    autoplay: false,
                    pager: false,
                    speed: 300,
                    scale: 1,
                    keypress: true,
                })

                $(document).on('click', '.lg-toogle-thumb', () => {
                    $(document).find('.lg-sub-html').toggleClass('inactive')
                })
            }
        }
    }
}

$(() => {
    new GalleryManagement().init()
})
