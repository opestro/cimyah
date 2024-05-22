$(() => {
    const $reviewListContainer = $('.review-list-container')
    let imagesReviewBuffer = []

    const initLightGallery = (element) => {
        element.lightGallery({
            thumbnail: true,
        })
    }

    const getReviewList = (url, successCallback) => {
        if (!url) {
            return
        }

        $.ajax({
            url: url,
            method: 'GET',
            beforeSend: () => {
                $reviewListContainer.append('<div class="loading-spinner"></div>')
            },
            success: ({ data, message }) => {
                $reviewListContainer.find('h4').text(message)
                $reviewListContainer.find('.review-list').html(data)

                initLightGallery($reviewListContainer.find('.review-images'))

                if (successCallback) {
                    successCallback()
                }
            },
            complete: () => {
                $reviewListContainer.find('.loading-spinner').remove()
            },
        })
    }

    const loadPreviewImage = function (input) {
        const $uploadText = $('.image-upload__text')
        const maxFiles = $(input).data('max-files')
        const filesAmount = input.files.length

        if (maxFiles) {
            if (filesAmount >= maxFiles) {
                $uploadText.closest('.image-upload__uploader-container').addClass('d-none')
            } else {
                $uploadText.closest('.image-upload__uploader-container').removeClass('d-none')
            }
            $uploadText.text(filesAmount + '/' + maxFiles)
        } else {
            $uploadText.text(filesAmount)
        }
        const viewerList = $('.image-viewer__list')
        const $template = $('#review-image-template').html()

        viewerList.find('.image-viewer__item').remove()

        if (filesAmount) {
            for (let i = filesAmount - 1; i >= 0; i--) {
                viewerList.prepend($template.replace('__id__', i))
            }
            for (let j = filesAmount - 1; j >= 0; j--) {
                let reader = new FileReader()
                reader.onload = function (event) {
                    viewerList
                        .find('.image-viewer__item[data-id=' + j + ']')
                        .find('img')
                        .attr('src', event.target.result)
                }
                reader.readAsDataURL(input.files[j])
            }
        }
    }

    const setImagesFormReview = function (input) {
        const dT = new ClipboardEvent('').clipboardData || new DataTransfer()

        for (let file of imagesReviewBuffer) {
            dT.items.add(file)
        }

        input.files = dT.files
        loadPreviewImage(input)
    }

    if ($reviewListContainer.length) {
        initLightGallery($('.review-images'))
        getReviewList($reviewListContainer.data('ajax-url'))
    }

    $reviewListContainer.on('click', '.pagination a', (e) => {
        e.preventDefault()

        const url = $(e.currentTarget).prop('href')

        getReviewList(url, () => {
            $('html, body').animate({
                scrollTop: $reviewListContainer.offset().top - 130,
            })
        })
    })

    $(document).on('submit', '.product-review-container form', (e) => {
        e.preventDefault()
        e.stopPropagation()

        const $form = $(e.currentTarget)
        const $button = $form.find('button[type="submit"]')

        $.ajax({
            type: 'POST',
            cache: false,
            url: $form.prop('action'),
            data: new FormData($form[0]),
            contentType: false,
            processData: false,
            beforeSend: () => {
                $button.prop('disabled', true).addClass('loading')
            },
            success: ({ error, message }) => {
                if (!error) {
                    $form.find('select').val(0)
                    $form.find('textarea').val('')
                    $form.find('input[type=file]').val('')
                    $form.find('input.custom-field').val('')
                    imagesReviewBuffer = []

                    Theme.showSuccess(message)

                    getReviewList($reviewListContainer.data('ajax-url'), () => {
                        if (!$('.review-list').length) {
                            setTimeout(() => window.location.reload(), 1000)
                        }
                    })
                } else {
                    Theme.showError(message)
                }
            },
            error: (error) => {
                Theme.handleError(error, $form)
            },
            complete: () => {
                $button.prop('disabled', false).removeClass('loading')
            },
        })
    })

    $(document).on('change', '.product-review-container form input[type=file]', function (event) {
        event.preventDefault()

        const input = this
        const $input = $(input)
        const maxSize = $input.data('max-size')

        Object.keys(input.files).map(function (i) {
            if (maxSize && input.files[i].size / 1024 > maxSize) {
                const message = $input
                    .data('max-size-message')
                    .replace('__attribute__', input.files[i].name)
                    .replace('__max__', maxSize)
                Theme.showError(message)
            } else {
                imagesReviewBuffer.push(input.files[i])
            }
        })

        const filesAmount = imagesReviewBuffer.length
        const maxFiles = $input.data('max-files')
        if (maxFiles && filesAmount > maxFiles) {
            imagesReviewBuffer.splice(filesAmount - maxFiles - 1, filesAmount - maxFiles)
        }

        setImagesFormReview(input)
    })

    $(document).on('click', '.product-review-container form .image-viewer__icon-remove', function (event) {
        event.preventDefault()
        const $this = $(event.currentTarget)
        let id = $this.closest('.image-viewer__item').data('id')
        imagesReviewBuffer.splice(id, 1)

        let input = $('.product-review-container form input[type=file]')[0]
        setImagesFormReview(input)
    })

    if (sessionStorage.reloadReviewsTab) {
        if ($('#product-detail-tabs a[href="#product-reviews"]').length) {
            new bootstrap.Tab($('#product-detail-tabs a[href="#product-reviews"]')[0]).show()
        }

        sessionStorage.reloadReviewsTab = false
    }
})
