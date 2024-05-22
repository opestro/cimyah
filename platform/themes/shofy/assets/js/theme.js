/***************************************************
==================== JS INDEX ======================
****************************************************
01. PreLoader Js
02. Mobile Menu Js
03. Common Js
04. Menu Controls JS
05. Offcanvas Js
06. Search Js
07. cartmini Js
08. filter
09. Body overlay Js
10. Sticky Header Js
11. Theme Settings Js
12. Nice Select Js
13. Smooth Scroll Js
14. Slider Activation Area Start
15. Masonary Js
16. Wow Js
17. Counter Js
18. InHover Active Js
19. Line Animation Js
20. Video Play Js
21. Password Toggle Js
****************************************************/

$(() => {
    'use strict'

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').prop('content'),
        },
    })

    window.Theme = window.Theme || {}

    window.Theme.isRtl = () => {
        return document.body.getAttribute('dir') === 'rtl'
    }

    const windowOn = $(window)
    ////////////////////////////////////////////////////
    // 01. PreLoader Js
    windowOn.on('load', function () {
        $('#loading').fadeOut(500)
    })

    ////////////////////////////////////////////////////
    // 02. Mobile Menu Js
    $('#mobile-menu').meanmenu({
        meanMenuContainer: '.mobile-menu',
        meanScreenWidth: '991',
        meanExpand: [
            `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>`,
        ],
    })

    ////////////////////////////////////////////////////
    // 03. Common Js

    let initCommonJS = () => {
        const windowWidth = $(window).width()

        $('[data-background]').each(function () {
            const $element = $(this)

            let backgroundImage = $element.data('background')

            if (windowWidth > 768 && windowWidth < 1200) {
                if ($element.data('tablet-background')) {
                    backgroundImage = $element.data('tablet-background')
                }
            } else if (windowWidth <= 768) {
                if ($element.data('mobile-background')) {
                    backgroundImage = $element.data('mobile-background')
                }
            }

            if (backgroundImage) {
                $element.css({ 'background-image': 'url("' + encodeURI(backgroundImage) + '")' })
            }
        })

        $('[data-width]').each(function () {
            $(this).css('width', $(this).data('width'))
        })

        $('[data-bg-color]').each(function () {
            $(this).css('background-color', $(this).data('bg-color'))
        })

        $('[data-text-color]').each(function () {
            $(this).css('color', $(this).data('text-color'))
        })
    }

    initCommonJS()

    document.addEventListener('shortcode.loaded', () => {
        initCommonJS()
    })

    if ($('.main-menu.menu-style-3 > nav > ul').length > 0) {
        $('.main-menu.menu-style-3 > nav > ul').append(`<li id="marker" class="tp-menu-line"></li>`)
    }

    if ($('#tp-offcanvas-lang-toggle').length > 0) {
        window.addEventListener('click', function (e) {
            if (document.getElementById('tp-offcanvas-lang-toggle').contains(e.target)) {
                $('.tp-lang-list').toggleClass('tp-lang-list-open')
            } else {
                $('.tp-lang-list').removeClass('tp-lang-list-open')
            }
        })
    }

    if ($('#tp-offcanvas-currency-toggle').length > 0) {
        window.addEventListener('click', function (e) {
            if (document.getElementById('tp-offcanvas-currency-toggle').contains(e.target)) {
                $('.tp-currency-list').toggleClass('tp-currency-list-open')
            } else {
                $('.tp-currency-list').removeClass('tp-currency-list-open')
            }
        })
    }

    // for header language
    if ($('#tp-header-lang-toggle').length > 0) {
        window.addEventListener('click', function (e) {
            if (document.getElementById('tp-header-lang-toggle').contains(e.target)) {
                $('.tp-header-lang ul').toggleClass('tp-lang-list-open')
            } else {
                $('.tp-header-lang ul').removeClass('tp-lang-list-open')
            }
        })
    }

    // for header currency
    if ($('#tp-header-currency-toggle').length > 0) {
        window.addEventListener('click', function (e) {
            if (document.getElementById('tp-header-currency-toggle').contains(e.target)) {
                $('.tp-header-currency ul').toggleClass('tp-currency-list-open')
            } else {
                $('.tp-header-currency ul').removeClass('tp-currency-list-open')
            }
        })
    }

    // for header setting
    if ($('#tp-header-setting-toggle').length > 0) {
        window.addEventListener('click', function (e) {
            if (document.getElementById('tp-header-setting-toggle').contains(e.target)) {
                $('.tp-header-setting ul').toggleClass('tp-setting-list-open')
            } else {
                $('.tp-header-setting ul').removeClass('tp-setting-list-open')
            }
        })
    }

    $('.tp-hamburger-toggle').on('click', function () {
        $('.tp-header-side-menu').slideToggle('tp-header-side-menu')
    })

    const initMobileCategoriesDropdown = () => {
        if ($('.tp-category-menu-content').length && $('.tp-category-mobile-menu').length) {
            let navContent = document.querySelector('.tp-category-menu-content').outerHTML
            let mobileNavContainer = document.querySelector('.tp-category-mobile-menu')
            mobileNavContainer.innerHTML = navContent

            $('.tp-offcanvas-category-toggle').on('click', function () {
                $(this).siblings().find('nav').slideToggle()
            })

            let arrow = $('.tp-category-mobile-menu .has-dropdown > a')

            arrow.each(function () {
                let self = $(this)
                let arrowBtn = document.createElement('button')
                arrowBtn.title = 'toggle button'
                arrowBtn.classList.add('dropdown-toggle-btn')
                arrowBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>`

                self.append(function () {
                    return arrowBtn
                })

                self.find('button').on('click', function (e) {
                    e.preventDefault()
                    let self = $(this)
                    self.toggleClass('dropdown-opened')
                    self.parent().toggleClass('expanded')
                    self.parent().parent().addClass('dropdown-opened').siblings().removeClass('dropdown-opened')
                    self.parent().parent().children('.tp-submenu').slideToggle()
                })
            })
        }
    }

    ////////////////////////////////////////////////////
    // 04. Menu Controls JS
    document.addEventListener('ecommerce.categories-dropdown.success', () => {
        initMobileCategoriesDropdown()
    })

    if ($('.tp-main-menu-content').length && $('.tp-main-menu-mobile').length) {
        let navContent = document.querySelector('.tp-main-menu-content').outerHTML
        let mobileNavContainer = document.querySelector('.tp-main-menu-mobile')
        mobileNavContainer.innerHTML = navContent

        let arrow = $('.tp-main-menu-mobile .has-dropdown > a')

        arrow.each(function () {
            let self = $(this)
            let arrowBtn = document.createElement('button')
            arrowBtn.title = 'toggle button'
            arrowBtn.classList.add('dropdown-toggle-btn')
            arrowBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>`

            self.append(function () {
                return arrowBtn
            })

            self.find('button').on('click', function (e) {
                e.preventDefault()
                let self = $(this)
                self.toggleClass('dropdown-opened')
                self.parent().toggleClass('expanded')
                self.parent().parent().addClass('dropdown-opened').siblings().removeClass('dropdown-opened')
                self.parent().parent().children('.tp-submenu').slideToggle()
            })
        })
    }

    ////////////////////////////////////////////////////
    // 05. Offcanvas Js
    $('.tp-offcanvas-open-btn').on('click', function () {
        $('.offcanvas__area').addClass('offcanvas-opened')
        $('.body-overlay').addClass('opened')
    })
    $('.offcanvas-close-btn').on('click', function () {
        $('.offcanvas__area').removeClass('offcanvas-opened')
        $('.body-overlay').removeClass('opened')
    })

    ////////////////////////////////////////////////////
    // 06. Search Js
    $('.tp-search-open-btn').on('click', function () {
        $('.tp-search-area').addClass('opened')
        $('.body-overlay').addClass('opened')
    })
    $('.tp-search-close-btn').on('click', function () {
        $('.tp-search-area').removeClass('opened')
        $('.body-overlay').removeClass('opened')
    })

    ////////////////////////////////////////////////////
    $(document)
        .on('click', '.cartmini-open-btn', function () {
            $('.cartmini__area').addClass('cartmini-opened')
            $('.body-overlay').addClass('opened')
        })
        .on('click', '.cartmini-close-btn', function () {
            $('.cartmini__area').removeClass('cartmini-opened')
            $('.body-overlay').removeClass('opened')
        })
        .on('click', '.body-overlay', function () {
            $('.offcanvas__area').removeClass('offcanvas-opened')
            $('.tp-search-area').removeClass('opened')
            $('.cartmini__area').removeClass('cartmini-opened')
            $('.body-overlay').removeClass('opened')
        })

    ////////////////////////////////////////////////////
    // 10. Sticky Header Js
    windowOn.on('scroll', function () {
        canStickyHeader('#header-sticky')
        canStickyHeader('#header-sticky-2')
    })

    const canStickyHeader = (element) => {
        const width = window.innerWidth
        const scroll = $(window).scrollTop()
        const header = $(element)

        if (
            !!(
                (width > 991 && header.data('sticky') !== undefined) ||
                (width < 992 && header.data('mobile-sticky') !== undefined)
            )
        ) {
            if (scroll < 100) {
                header.removeClass(header.prop('id'))
            } else {
                header.addClass(header.prop('id'))
            }
        }
    }

    windowOn.on('scroll', function () {
        const scroll = $(window).scrollTop()
        if (scroll < 100) {
            $('.tp-side-menu-5').removeClass('sticky-active')
        } else {
            $('.tp-side-menu-5').addClass('sticky-active')
        }
    })

    if ($.fn.niceSelect) {
        $('[data-nice-select]').niceSelect()
    }

    ////////////////////////////////////////////////////
    // 13. Smooth Scroll Js
    function smoothScroll() {
        $('.smooth a').on('click', function (event) {
            const target = $(this.getAttribute('href'))
            if (target.length) {
                event.preventDefault()
                $('html, body')
                    .stop()
                    .animate(
                        {
                            scrollTop: target.offset().top - 120,
                        },
                        1500
                    )
            }
        })
    }
    smoothScroll()

    function back_to_top() {
        const btn = $('#back_to_top')
        const btn_wrapper = $('.back-to-top-wrapper')

        windowOn.scroll(function () {
            if (windowOn.scrollTop() > 300) {
                btn_wrapper.addClass('back-to-top-btn-show')
            } else {
                btn_wrapper.removeClass('back-to-top-btn-show')
            }
        })

        btn.on('click', function (e) {
            e.preventDefault()
            $('html, body').animate({ scrollTop: 0 }, '300')
        })
    }
    back_to_top()

    ////////////////////////////////////////////////////

    const initSlick = () => {
        const $sliderActive4 = $('.tp-slider-active-4')

        $('.tp-slider-active-4').slick({
            infinite: true,
            autoplay: !!$sliderActive4.data('autoplay'),
            autoplaySpeed: $sliderActive4.data('autoplay-speed') || 5000,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            fade: true,
            centerMode: true,
            prevArrow: `<button type="button" class="tp-slider-3-button-prev"><svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
		   <path d="M1.00073 6.99989L15 6.99989" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
		   <path d="M6.64648 1.5L1.00011 6.99954L6.64648 12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>`,
            nextArrow: `<button type="button" class="tp-slider-3-button-next"><svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M14.9993 6.99989L1 6.99989" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
		<path d="M9.35352 1.5L14.9999 6.99954L9.35352 12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
		</svg></button>`,
            asNavFor: '.tp-slider-nav-active',
            appendArrows: $('.tp-slider-arrow-4'),
        })

        $('.tp-slider-nav-active').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            vertical: true,
            asNavFor: '.tp-slider-active-4',
            dots: false,
            arrows: false,
            centerMode: false,
            focusOnSelect: true,
        })
    }

    const initSwiper = () => {
        const $mainSlider = $('.tp-slider-active')
        const mainSlider = new Swiper('.tp-slider-active', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: $mainSlider.data('loop'),
            rtl: Theme.isRtl(),
            autoplay: $mainSlider.data('autoplay')
                ? {
                      delay: $mainSlider.data('autoplay-speed') || 5000,
                  }
                : {},
            effect: 'fade',
            // Navigation arrows
            navigation: {
                nextEl: '.tp-slider-button-next',
                prevEl: '.tp-slider-button-prev',
            },
            pagination: {
                el: '.tp-slider-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
        })
        mainSlider.on('slideChangeTransitionStart', function () {
            if ($('.swiper-slide.swiper-slide-active, .tp-slider-item .is-light').hasClass('is-light')) {
                $('.tp-slider-variation').addClass('is-light')
            } else {
                $('.tp-slider-variation').removeClass('is-light')
            }
        })

        new Swiper('.tp-gallery-slider', {
            slidesPerView: 5,
            spaceBetween: 20,
            loop: false,
            rtl: Theme.isRtl(),
            breakpoints: {
                1200: {
                    slidesPerView: 5,
                },
                992: {
                    slidesPerView: 4,
                },
                768: {
                    slidesPerView: 3,
                },
                0: {
                    slidesPerView: 2,
                },
            },
        })

        // home electronics
        new Swiper('.shop-mega-menu-slider-active', {
            slidesPerView: 3,
            spaceBetween: 20,
            loop: true,
            rtl: Theme.isRtl(),
            // Navigation arrows
            navigation: {
                nextEl: '.tp-shop-mega-menu-button-next',
                prevEl: '.tp-shop-mega-menu-button-prev',
            },
            pagination: {
                el: '.tp-shop-mega-menu-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
        })

        new Swiper('.tp-product-categories-slider', {
            slidesPerView: 5,
            loop: false,
            rtl: Theme.isRtl(),
            observer: true,
            spaceBetween: 20,
            breakpoints: {
                1200: {
                    slidesPerView: 5,
                },
                992: {
                    slidesPerView: 4,
                },
                768: {
                    slidesPerView: 3,
                },
                576: {
                    slidesPerView: 2,
                },
                0: {
                    slidesPerView: 2,
                },
            },
        })

        // home electronics
        new Swiper('.tp-blog-main-slider-active', {
            slidesPerView: 3,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 4000,
            },
            rtl: Theme.isRtl(),
            // Navigation arrows
            navigation: {
                nextEl: '.tp-blog-main-slider-button-next',
                prevEl: '.tp-blog-main-slider-button-prev',
            },
            pagination: {
                el: '.tp-blog-main-slider-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
            breakpoints: {
                1200: {
                    slidesPerView: 3,
                },
                992: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 2,
                },
                576: {
                    slidesPerView: 1,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        })

        const $sliderActive2 = $('.tp-slider-active-2')

        // home 2 fashion
        new Swiper('.tp-slider-active-2', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: $sliderActive2.data('loop'),
            autoplay: $sliderActive2.data('autoplay')
                ? {
                      delay: $sliderActive2.data('autoplay-speed') || 5000,
                  }
                : {},
            rtl: Theme.isRtl(),
            effect: 'fade',
            // Navigation arrows
            navigation: {
                nextEl: '.tp-slider-2-button-next',
                prevEl: '.tp-slider-2-button-prev',
            },
            pagination: {
                el: '.tp-slider-2-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
        })

        const $sliderActive3 = $('.tp-slider-active-3')

        // home 3 beauty
        new Swiper('.tp-slider-active-3', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: $sliderActive3.data('loop'),
            autoplay: $sliderActive3.data('autoplay')
                ? {
                      delay: $sliderActive3.data('autoplay-speed') || 5000,
                  }
                : {},
            rtl: Theme.isRtl(),
            effect: 'fade',
            // Navigation arrows
            navigation: {
                nextEl: '.tp-slider-3-button-next',
                prevEl: '.tp-slider-3-button-prev',
            },
            pagination: {
                el: '.tp-slider-3-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
        })

        const $sliderActive5 = $('.tp-slider-active-5')

        // home 5 grocery
        new Swiper('.tp-slider-active-5', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: $sliderActive5.data('loop'),
            autoplay: $sliderActive5.data('autoplay')
                ? {
                      delay: $sliderActive5.data('autoplay-speed') || 5000,
                  }
                : {},
            rtl: Theme.isRtl(),
            effect: 'fade',
            // Navigation arrows
            navigation: {
                nextEl: '.tp-slider-5-button-next',
                prevEl: '.tp-slider-5-button-prev',
            },
            pagination: {
                el: '.tp-slider-5-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
        })

        new Swiper('.tp-slider-nav-actives', {
            slidesPerView: 3,
            spaceBetween: 20,
            loop: true,
            direction: 'vertical',
        })

        // home 3 beauty
        new Swiper('.tp-slider-active-4s', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            rtl: Theme.isRtl(),
            effect: 'fade',
            // Navigation arrows
            navigation: {
                nextEl: '.tp-slider-3-button-next',
                prevEl: '.tp-slider-3-button-prev',
            },
            pagination: {
                el: '.tp-slider-3-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
        })

        // home 3 beauty
        new Swiper('.tp-slider-nav-actives', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            rtl: Theme.isRtl(),
            effect: 'fade',
            // Navigation arrows
            navigation: {
                nextEl: '.tp-slider-3-button-next',
                prevEl: '.tp-slider-3-button-prev',
            },
            pagination: {
                el: '.tp-slider-3-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
        })

        new Swiper('.tp-product-offer-slider-active', {
            slidesPerView: 4,
            spaceBetween: 30,
            loop: true,
            rtl: Theme.isRtl(),
            pagination: {
                el: '.tp-deals-slider-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
            breakpoints: {
                1200: {
                    slidesPerView: 3,
                },
                992: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 2,
                },
                576: {
                    slidesPerView: 1,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        })

        new Swiper('.tp-product-arrival-active', {
            slidesPerView: 4,
            spaceBetween: 30,
            loop: false,
            rtl: Theme.isRtl(),
            pagination: {
                el: '.tp-arrival-slider-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return `<span class="${className}"><button>${index + 1}</button></span>`
                },
            },
            // Navigation arrows
            navigation: {
                nextEl: '.tp-arrival-slider-button-next',
                prevEl: '.tp-arrival-slider-button-prev',
            },
            breakpoints: {
                1200: {
                    slidesPerView: $('.tp-product-arrival-active').data('items-per-view') || 4,
                },
                992: {
                    slidesPerView: 3,
                },
                768: {
                    slidesPerView: 2,
                },
                576: {
                    slidesPerView: 2,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        })

        new Swiper('.tp-product-banner-slider-active', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            effect: 'fade',
            pagination: {
                el: '.tp-product-banner-slider-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
        })

        new Swiper('.tp-product-gadget-banner-slider-active', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            effect: 'fade',
            pagination: {
                el: '.tp-product-gadget-banner-slider-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
        })

        new Swiper('.tp-category-slider-active-2', {
            slidesPerView: 5,
            spaceBetween: 20,
            loop: false,
            rtl: Theme.isRtl(),
            enteredSlides: false,
            pagination: {
                el: '.tp-category-slider-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
            // Navigation arrows
            navigation: {
                nextEl: '.tp-category-slider-button-next',
                prevEl: '.tp-category-slider-button-prev',
            },
            scrollbar: {
                el: '.swiper-scrollbar',
                draggable: true,
                dragClass: 'tp-swiper-scrollbar-drag',
                snapOnRelease: true,
            },
            breakpoints: {
                1200: {
                    slidesPerView: 5,
                },
                992: {
                    slidesPerView: 4,
                },
                768: {
                    slidesPerView: 3,
                },
                576: {
                    slidesPerView: 2,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        })

        new Swiper('.tp-featured-slider-active', {
            slidesPerView: 3,
            spaceBetween: 10,
            loop: true,
            rtl: Theme.isRtl(),
            enteredSlides: false,
            pagination: {
                el: '.tp-featured-slider-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return `<span class="${className}"><button>${index + 1}</button></span>`
                },
            },
            // Navigation arrows
            navigation: {
                nextEl: '.tp-featured-slider-button-next',
                prevEl: '.tp-featured-slider-button-prev',
            },

            breakpoints: {
                1200: {
                    slidesPerView: 3,
                },
                992: {
                    slidesPerView: 3,
                },
                768: {
                    slidesPerView: 2,
                },
                576: {
                    slidesPerView: 1,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        })

        new Swiper('.tp-product-related-slider-active', {
            slidesPerView: 4,
            spaceBetween: 24,
            loop: false,
            rtl: Theme.isRtl(),
            enteredSlides: false,
            pagination: {
                el: '.tp-related-slider-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
            // Navigation arrows
            navigation: {
                nextEl: '.tp-related-slider-button-next',
                prevEl: '.tp-related-slider-button-prev',
            },

            scrollbar: {
                el: '.tp-related-swiper-scrollbar',
                draggable: true,
                dragClass: 'tp-swiper-scrollbar-drag',
                snapOnRelease: true,
            },

            breakpoints: {
                1200: {
                    slidesPerView: 4,
                },
                992: {
                    slidesPerView: 3,
                },
                768: {
                    slidesPerView: 2,
                },
                576: {
                    slidesPerView: 2,
                },
                0: {
                    slidesPerView: 2,
                    spaceBetween: 10,
                },
            },
        })

        new Swiper('.tp-product-cross-sale-slider-active', {
            slidesPerView: 4,
            spaceBetween: 24,
            loop: false,
            rtl: Theme.isRtl(),
            enteredSlides: false,
            scrollbar: {
                el: '.tp-cross-sale-swiper-scrollbar',
                draggable: true,
                dragClass: 'tp-swiper-scrollbar-drag',
                snapOnRelease: true,
            },

            breakpoints: {
                0: {
                    slidesPerView: 2,
                },
                576: {
                    slidesPerView: 3,
                },
                992: {
                    slidesPerView: 4,
                },
                1200: {
                    slidesPerView: 5,
                },
                1400: {
                    slidesPerView: 6,
                },
            },
        })

        new Swiper('.tp-testimoinal-slider-active-3', {
            slidesPerView: 2,
            spaceBetween: 24,
            loop: true,
            rtl: Theme.isRtl(),
            enteredSlides: false,
            pagination: {
                el: '.tp-testimoinal-slider-dot-3',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
            // Navigation arrows
            navigation: {
                nextEl: '.tp-testimoinal-slider-button-next-3',
                prevEl: '.tp-testimoinal-slider-button-prev-3',
            },

            breakpoints: {
                1200: {
                    slidesPerView: 2,
                },
                992: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 1,
                },
                576: {
                    slidesPerView: 1,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        })

        new Swiper('.tp-category-slider-active-4', {
            slidesPerView: 5,
            spaceBetween: 25,
            loop: true,
            rtl: Theme.isRtl(),
            enteredSlides: false,
            pagination: {
                el: '.tp-category-slider-dot-4',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
            // Navigation arrows
            navigation: {
                nextEl: '.tp-category-slider-button-next-4',
                prevEl: '.tp-category-slider-button-prev-4',
            },

            scrollbar: {
                el: '.tp-category-swiper-scrollbar',
                draggable: true,
                dragClass: 'tp-swiper-scrollbar-drag',
                snapOnRelease: true,
            },

            breakpoints: {
                1400: {
                    slidesPerView: 5,
                },
                1200: {
                    slidesPerView: 4,
                },
                992: {
                    slidesPerView: 3,
                },
                768: {
                    slidesPerView: 2,
                },
                576: {
                    slidesPerView: 2,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        })

        new Swiper('.tp-brand-slider-active', {
            slidesPerView: 5,
            spaceBetween: 0,
            loop: true,
            rtl: Theme.isRtl(),
            enteredSlides: false,
            pagination: {
                el: '.tp-brand-slider-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return `<span class="${className}"><button>${index + 1}</button></span>`
                },
            },
            // Navigation arrows
            navigation: {
                nextEl: '.tp-brand-slider-button-next',
                prevEl: '.tp-brand-slider-button-prev',
            },

            breakpoints: {
                1200: {
                    slidesPerView: 5,
                },
                992: {
                    slidesPerView: 5,
                },
                768: {
                    slidesPerView: 4,
                },
                576: {
                    slidesPerView: 3,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        })

        new Swiper('.tp-best-slider-active', {
            slidesPerView: 4,
            spaceBetween: 24,
            loop: true,
            rtl: Theme.isRtl(),
            enteredSlides: false,
            pagination: {
                el: '.tp-best-slider-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return `<span class="${className}"><button>${index + 1}</button></span>`
                },
            },
            // Navigation arrows
            navigation: {
                nextEl: '.tp-best-slider-button-next',
                prevEl: '.tp-best-slider-button-prev',
            },

            scrollbar: {
                el: '.tp-best-swiper-scrollbar',
                draggable: true,
                dragClass: 'tp-swiper-scrollbar-drag',
                snapOnRelease: true,
            },

            breakpoints: {
                1200: {
                    slidesPerView: 4,
                },
                992: {
                    slidesPerView: 4,
                },
                768: {
                    slidesPerView: 2,
                },
                576: {
                    slidesPerView: 2,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        })

        new Swiper('.tp-slider-active-5s', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            rtl: Theme.isRtl(),
            effect: 'fade',
        })

        new Swiper('.tp-category-slider-active-5', {
            slidesPerView: 6,
            spaceBetween: 12,
            loop: true,
            rtl: Theme.isRtl(),
            enteredSlides: false,
            pagination: {
                el: '.tp-category-slider-dot-4',
                clickable: true,
                renderBullet: function (index, className) {
                    return `<span class="${className}"><button>${index + 1}</button></span>`
                },
            },
            // Navigation arrows
            navigation: {
                nextEl: '.tp-category-slider-button-next-5',
                prevEl: '.tp-category-slider-button-prev-5',
            },

            scrollbar: {
                el: '.tp-category-5-swiper-scrollbar',
                draggable: true,
                dragClass: 'tp-swiper-scrollbar-drag',
                snapOnRelease: true,
            },

            breakpoints: {
                1400: {
                    slidesPerView: 6,
                },
                1200: {
                    slidesPerView: 5,
                },
                992: {
                    slidesPerView: 4,
                },
                768: {
                    slidesPerView: 3,
                },
                576: {
                    slidesPerView: 2,
                },
                400: {
                    slidesPerView: 2,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        })

        if ($('.tp-best-slider-active-5')) {
            const $bestSliderActive5 = $('.tp-best-slider-active-5')
            const itemPerRow = $bestSliderActive5.data('item-per-row')

            new Swiper('.tp-best-slider-active-5', {
                slidesPerView: itemPerRow,
                spaceBetween: 24,
                loop: true,
                rtl: Theme.isRtl(),
                enteredSlides: false,
                pagination: {
                    el: '.tp-best-slider-dot-5',
                    clickable: true,
                    renderBullet: function (index, className) {
                        return `<span class="${className}"><button>${index + 1}</button></span>`
                    },
                },
                // Navigation arrows
                navigation: {
                    nextEl: '.tp-best-slider-5-button-next',
                    prevEl: '.tp-best-slider-5-button-prev',
                },

                scrollbar: {
                    el: '.tp-best-5-swiper-scrollbar',
                    draggable: true,
                    dragClass: 'tp-swiper-scrollbar-drag',
                    snapOnRelease: true,
                },

                breakpoints: {
                    1200: {
                        slidesPerView: itemPerRow,
                    },
                    992: {
                        slidesPerView: itemPerRow - 1,
                    },
                    768: {
                        slidesPerView: itemPerRow - 1,
                    },
                    576: {
                        slidesPerView: itemPerRow - 2,
                    },
                },
            })
        }

        new Swiper('.tp-product-details-thumb-slider-active', {
            slidesPerView: 2,
            spaceBetween: 13,
            loop: true,
            rtl: Theme.isRtl(),
            enteredSlides: false,
            pagination: {
                el: '.tp-product-details-thumb-slider-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
            // Navigation arrows
            navigation: {
                nextEl: '.tp-product-details-thumb-slider-5-button-next',
                prevEl: '.tp-product-details-thumb-slider-5-button-prev',
            },

            breakpoints: {
                1200: {
                    slidesPerView: 2,
                },
                992: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 2,
                },
                576: {
                    slidesPerView: 2,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        })

        const $trendingSliderActive = $('.tp-trending-slider-active')

        if ($trendingSliderActive.length > 0) {
            const itemsPerView = $trendingSliderActive.data('items-per-view') || 2

            new Swiper('.tp-trending-slider-active', {
                slidesPerView: itemsPerView,
                spaceBetween: 24,
                loop: true,
                rtl: Theme.isRtl(),
                enteredSlides: false,
                pagination: {
                    el: '.tp-trending-slider-dot',
                    clickable: true,
                    renderBullet: function (index, className) {
                        return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                    },
                },
                // Navigation arrows
                navigation: {
                    nextEl: '.tp-trending-slider-button-next',
                    prevEl: '.tp-trending-slider-button-prev',
                },

                breakpoints: {
                    1200: {
                        slidesPerView: itemsPerView,
                    },
                    992: {
                        slidesPerView: itemsPerView - 1,
                    },
                    768: {
                        slidesPerView: itemsPerView - 1,
                    },
                    576: {
                        slidesPerView: itemsPerView - 2,
                    },
                    0: {
                        slidesPerView: 1,
                    },
                },
            })
        }


        new Swiper('.tp-special-slider-active', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            rtl: Theme.isRtl(),
            effect: 'fade',
            enteredSlides: false,
            pagination: {
                el: '.tp-special-slider-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
            // Navigation arrows
            navigation: {
                nextEl: '.tp-special-slider-button-next',
                prevEl: '.tp-special-slider-button-prev',
            },
        })

        new Swiper('.tp-testimonial-slider-active', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            rtl: Theme.isRtl(),
            pagination: {
                el: '.tp-testimonial-slider-dot',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
            // Navigation arrows
            navigation: {
                nextEl: '.tp-testimonial-slider-button-next',
                prevEl: '.tp-testimonial-slider-button-prev',
            },
        })

        new Swiper('.tp-testimonial-slider-active-5', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            rtl: Theme.isRtl(),
            enteredSlides: false,
            pagination: {
                el: '.tp-testimonial-slider-dot-5',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
            // Navigation arrows
            navigation: {
                nextEl: '.tp-testimonial-slider-5-button-next',
                prevEl: '.tp-testimonial-slider-5-button-prev',
            },
        })

        new Swiper('.tp-best-banner-slider-active-5', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            rtl: Theme.isRtl(),
            enteredSlides: false,
            effect: 'fade',
            pagination: {
                el: '.tp-best-banner-slider-dot-5',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<button>' + (index + 1) + '</button>' + '</span>'
                },
            },
            // Navigation arrows
            navigation: {
                nextEl: '.tp-best-banner-slider-5-button-next',
                prevEl: '.tp-best-banner-slider-5-button-prev',
            },
        })

        new Swiper('.tp-postbox-slider', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            rtl: Theme.isRtl(),
            autoplay: {
                delay: 3000,
            },
            // Navigation arrows
            navigation: {
                nextEl: '.tp-postbox-slider-button-next',
                prevEl: '.tp-postbox-slider-button-prev',
            },
            breakpoints: {
                1200: {
                    slidesPerView: 1,
                },
                992: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 1,
                },
                576: {
                    slidesPerView: 1,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        })

        if (typeof Theme.lazyLoadInstance !== 'undefined') {
            Theme.lazyLoadInstance.update()
        }
    }

    initSwiper()
    initSlick()

    function tp_ecommerce() {
        $('.tp-checkout-payment-item label').on('click', function () {
            $(this).siblings('.tp-checkout-payment-desc').slideToggle(400)
        })

        $('.tp-color-variation-btn').on('click', function () {
            $(this).addClass('active').siblings().removeClass('active')
        })

        $('.tp-size-variation-btn').on('click', function () {
            $(this).addClass('active').siblings().removeClass('active')
        })

        ////////////////////////////////////////////////////
        // 17. Show Login Toggle Js
        $('.tp-checkout-login-form-reveal-btn').on('click', function () {
            $('#tpReturnCustomerLoginForm').slideToggle(400)
        })

        ////////////////////////////////////////////////////
        // 18. Show Coupon Toggle Js
        $('.tp-checkout-coupon-form-reveal-btn').on('click', function () {
            $('#tpCheckoutCouponForm').slideToggle(400)
        })

        ////////////////////////////////////////////////////
        // 19. Create An Account Toggle Js
        $('#cbox').on('click', function () {
            $('#cbox_info').slideToggle(900)
        })

        ////////////////////////////////////////////////////
        // 20. Shipping Box Toggle Js
        $('#ship-box').on('click', function () {
            $('#ship-box-info').slideToggle(1000)
        })
    }
    tp_ecommerce()

    ////////////////////////////////////////////////////
    // 18. InHover Active Js
    $('.hover__active').on('mouseenter', function () {
        $(this).addClass('active').parent().siblings().find('.hover__active').removeClass('active')
    })

    $('.activebsba').on('click', function () {
        $('#services-item-thumb').removeClass().addClass($(this).attr('rel'))
        $(this).addClass('active').siblings().removeClass('active')
    })

    ////////////////////////////////////////////////////
    // 19. Line Animation Js
    if ($('#marker').length > 0) {
        function tp_tab_line() {
            const marker = document.querySelector('#marker')
            const item = document.querySelectorAll('.menu-style-3  > nav > ul > li')
            const itemActive = document.querySelector('.menu-style-3  > nav > ul > li.active')

            function indicator(e) {
                marker.style.left = e.offsetLeft + 'px'
                marker.style.width = e.offsetWidth + 'px'
            }

            item.forEach((link) => {
                link.addEventListener('mouseenter', (e) => {
                    indicator(e.target)
                })
            })

            const activeNav = $('.menu-style-3 > nav > ul > li.active')
            const activewidth = $(activeNav).width()
            const activePadLeft = parseFloat($(activeNav).css('padding-left'))
            const activePadRight = parseFloat($(activeNav).css('padding-right'))
            const totalWidth = activewidth + activePadLeft + activePadRight

            const precedingAnchorWidth = anchorWidthCounter()

            $(marker).css('display', 'block')

            $(marker).css('width', totalWidth)

            function anchorWidthCounter() {
                let anchorWidths = 0
                let a
                let aWidth
                let aPadLeft
                let aPadRight
                let aTotalWidth
                $('.menu-style-3 > nav > ul > li').each(function (index, elem) {
                    const activeTest = $(elem).hasClass('active')
                    marker.style.left = elem.offsetLeft + 'px'
                    if (activeTest) {
                        // Break out of the each function.
                        return false
                    }

                    a = $(elem).find('li')
                    aWidth = a.width()
                    aPadLeft = parseFloat(a.css('padding-left'))
                    aPadRight = parseFloat(a.css('padding-right'))
                    aTotalWidth = aWidth + aPadLeft + aPadRight

                    anchorWidths = anchorWidths + aTotalWidth
                })

                return anchorWidths
            }
        }
        tp_tab_line()
    }

    function tp_tab_line_2() {
        const marker = document.querySelector('#productTabMarker')
        const item = document.querySelectorAll('.tp-product-tab button')
        const itemActive = document.querySelector('.tp-product-tab .nav-link.active')

        function indicator(e) {
            marker.style.left = e.offsetLeft + 'px'
            marker.style.width = e.offsetWidth + 'px'
        }

        item.forEach((link) => {
            link.addEventListener('click', (e) => {
                indicator(e.target)
            })
        })

        const activeNav = $('.nav-link.active')
        const activewidth = $(activeNav).width()
        const activePadLeft = parseFloat($(activeNav).css('padding-left'))
        const activePadRight = parseFloat($(activeNav).css('padding-right'))
        const totalWidth = activewidth + activePadLeft + activePadRight

        const precedingAnchorWidth = anchorWidthCounter()

        $(marker).css('display', 'block')

        $(marker).css('width', totalWidth)

        function anchorWidthCounter() {
            let anchorWidths = 0
            let a
            let aWidth
            let aPadLeft
            let aPadRight
            let aTotalWidth
            $('.tp-product-tab button').each(function (index, elem) {
                const activeTest = $(elem).hasClass('active')
                marker.style.left = elem.offsetLeft + 'px'
                if (activeTest) {
                    // Break out of the each function.
                    return false
                }

                a = $(elem).find('button')
                aWidth = a.width()
                aPadLeft = parseFloat(a.css('padding-left'))
                aPadRight = parseFloat(a.css('padding-right'))
                aTotalWidth = aWidth + aPadLeft + aPadRight

                anchorWidths = anchorWidths + aTotalWidth
            })

            return anchorWidths
        }
    }

    if ($('#productTabMarker').length > 0) {
        tp_tab_line_2()
    }

    ////////////////////////////////////////////////////
    // 20. Video Play Js
    let play = false
    $('.tp-video-toggle-btn').on('click', function () {
        if (play === false) {
            $('.tp-slider-video').addClass('full-width')
            $(this).addClass('hide')
            play = true

            $('.tp-slider-video')
                .find('video')
                .each(function () {
                    $(this).get(0).play()
                })
        } else {
            $('.tp-slider-video').removeClass('full-width')
            $(this).removeClass('hide')
            play = false
            $('.tp-slider-video')
                .find('video')
                .each(function () {
                    $(this).get(0).pause()
                })
        }
    })

    ////////////////////////////////////////////////////
    // 21. Password Toggle Js
    if ($('#password-show-toggle').length > 0) {
        const btn = document.getElementById('password-show-toggle')

        btn.addEventListener('click', function (e) {
            const inputType = document.getElementById('tp_password')
            const openEye = document.getElementById('open-eye')
            const closeEye = document.getElementById('close-eye')

            if (inputType.type === 'password') {
                inputType.type = 'text'
                openEye.style.display = 'block'
                closeEye.style.display = 'none'
            } else {
                inputType.type = 'password'
                openEye.style.display = 'none'
                closeEye.style.display = 'block'
            }
        })
    }

    if ($('.tp-header-height').length > 0) {
        const headerHeight = document.querySelector('.tp-header-height')
        const setHeaderHeight = headerHeight.offsetHeight

        $('.tp-header-height').each(function () {
            $(this).css({
                height: setHeaderHeight + 'px',
            })
        })
    }

    const $categoryLabel = $('.product-category-label span')

    $(document).on('change', '.tp-header-search-category select', function () {
        $categoryLabel.text($.trim($(this).find('option:selected').text()))
    })

    $categoryLabel.text($.trim($('.tp-header-search-category select').find('option:selected').text()))

    const collapseBreadcrumb = () => {
        const breadcrumbList = document.querySelector('.js_breadcrumb_reduce_length_on_mobile')
        if (breadcrumbList) {
            const breadcrumbItems = breadcrumbList.querySelectorAll('span')
            const totalItems = breadcrumbItems.length

            if (totalItems > 2) {
                for (let i = 1; i < totalItems - 1; i++) {
                    breadcrumbItems[i].style.display = 'none'
                }

                const ellipsis = document.createElement('span')
                ellipsis.textContent = '...'
                breadcrumbList.insertBefore(ellipsis, breadcrumbItems[totalItems - 1])
            }
        }
    }

    if ($(window).width() < 768) {
        collapseBreadcrumb()
    }

    if ($('.tp-product-details-sticky-actions').length > 0 && $('.tp-product-details-action-wrapper').length > 0) {
        new Waypoint({
            element: $('.tp-product-details-action-wrapper'),
            handler: function (direction) {
                if (direction === 'down') {
                    $('.tp-product-details-sticky-actions').addClass('active')
                } else {
                    $('.tp-product-details-sticky-actions').removeClass('active')
                }
            },
        })
    }

    document.addEventListener('shortcode.loaded', () => {
        initSwiper()

        if ($('#productTabMarker').length > 0) {
            tp_tab_line_2()
        }
    })

    $(document).find('[data-bb-toggle="block-lazy-loading"]').each((index, element) => {
        const $element = $(element)

        $.ajax({
            url: $element.data('url'),
            type: 'GET',
            success: ({ data }) => {
                $element.replaceWith(data)

                if (typeof Theme.lazyLoadInstance !== 'undefined') {
                    Theme.lazyLoadInstance.update()
                }

                initSwiper()
            },
            error: (error) => Theme.handleError(error),
        })
    })
})
