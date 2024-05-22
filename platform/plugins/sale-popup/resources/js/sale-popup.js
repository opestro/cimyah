let salesPopup = function ($popupContainer) {
    if ($(window).width() < 768) {
        return
    }

    const stt = $popupContainer.data('stt')

    if (stt === undefined) {
        return
    }

    const limit = stt.limit - 1
    const popupType = stt.pp_type
    const arrTitle = JSON.parse($('#title-sale-popup').html())
    const arrUrl = stt.url
    const arrImage = stt.image
    const arrID = stt.id
    const arrLocation = JSON.parse($('#location-sale-popup').html())
    const arrTime = JSON.parse($('#time-sale-popup').html())
    const classUp = stt.classUp
    const classDown = stt.classDown[classUp]
    let starTimeout
    let stayTimeout

    const salePopupImg = $('.js-sale-popup-img')
    const salePopupLink = $('.js-sale-popup-a')
    const salePopupTitle = $('.js-sale-popup-tt')
    const salePopupLocation = $('.js-sale-popup-location')
    const salePopupTimeAgo = $('.js-sale-popup-ago')
    const salePopupQuickView = $('.sale-popup-quick-view')

    let index = 0
    const min = 0
    const max = arrUrl.length - 1
    const max2 = arrLocation.length - 1
    const max3 = arrTime.length - 1
    const starTime = stt.starTime * stt.starTimeUnit
    const stayTime = stt.stayTime * stt.stayTimeUnit

    let getRandomInt = function (min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min
    }

    let updateData = function (index) {
        let img = arrImage[index]

        salePopupImg.attr('src', img).attr('srcset', img)

        salePopupTitle.text(arrTitle[index])

        salePopupLink.attr('href', arrUrl[index])

        const quickViewUrl = salePopupQuickView.attr('data-base-url') + '/ajax/quick-view/' + arrID[index]

        salePopupQuickView.attr('href', quickViewUrl).attr('data-url', quickViewUrl)

        salePopupLocation.text(arrLocation[getRandomInt(min, max2)])

        salePopupTimeAgo.text(arrTime[getRandomInt(min, max3)])

        showSalesPopUp()
    }

    let loadSalesPopup = function () {
        if (popupType == '1') {
            updateData(index)
            ++index
            if (index > limit || index > max) {
                index = 0
            }
        } else {
            updateData(getRandomInt(min, max))
        }

        stayTimeout = setTimeout(function () {
            unloadSalesPopup()
        }, stayTime)
    }

    let unloadSalesPopup = function () {
        hideSalesPopUp()
        starTimeout = setTimeout(function () {
            loadSalesPopup()
        }, starTime)
    }

    let showSalesPopUp = function () {
        $popupContainer.removeClass('hidden').addClass(classUp).removeClass(classDown)
    }

    let hideSalesPopUp = function () {
        $popupContainer.removeClass(classUp).addClass(classDown)
    }

    $(document).on('click', '.sale-popup-close', function (e) {
        e.preventDefault()
        hideSalesPopUp()
        clearTimeout(stayTimeout)
        clearTimeout(starTimeout)
    })

    $popupContainer.on('open-sale-popup', function () {
        unloadSalesPopup()
    })

    unloadSalesPopup()
}

$(document).ready(function () {
    const $popupContainer = $('.js-sale-popup-container.hidden')

    if ($popupContainer.length) {
        setTimeout(() => {
            $popupContainer.removeClass('hidden')

            $popupContainer.load($popupContainer.data('include'), function () {
                salesPopup($popupContainer.find('.sale-popup-container-wrap'))
            })
        }, 3000)
    }
})
