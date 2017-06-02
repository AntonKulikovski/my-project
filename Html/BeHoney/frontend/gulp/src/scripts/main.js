$(document).on('ready', function () {
    $("#main-slider").owlCarousel({
        nav: false,
        items: 1,
        loop: true,
        dotsContainer: '.js-slider-pag',
        autoplay: true,
        autoplayTimeout: 6500,
        onAnimationEnd: function (elem) {
            $('.slider-pag__item').removeClass('active');
            $('#' + this.currentItem).addClass('active');
            $('.packageImage').removeClass('js-img-move');
            $('#slide-' + this.currentItem).find('img').addClass("js-img-move");
        }
    });

    function initSlider(sliderAttr, visibleItem) {
        $(sliderAttr).owlCarousel({
            items: visibleItem,
            loop: true,
            nav: true,
            pagination: false,
            responsive: {
                0: {items: 1},
                450: {items: 2},
                768: {items: 3}
            }
        });
    }

    initSlider('.review__slider', 3);
    initSlider('#insta-slider', 4);

    var owl = $("#main-slider").data('owlCarousel');

    $('.slider-pag__item').on('click', function () {
        var slideNumber = $(this).attr('id');
        $("#main-slider").trigger('owl.goTo', slideNumber);
    });

    $('.js-open-menu').on('click', function () {
        $('.mob-nav').slideToggle(300);
        $('.hamb__wrap').toggleClass('open');
    });

    if ($(window).width() < 1025) {
        $('.drop .nav__item').on('click', function () {
            $(this).siblings('.drop-list__wrap').slideToggle(300);
            $(this).toggleClass('active');
        })
    }

    if ($(window).width() < 1025) {
        initSlider('.mobile-slider', 3);
    }

    $(window).on('resize', function () {
        if ($(window).width() < 769) {
            initSlider('.mobile-slider', 3);
        }
        else {
            $('.mobile-slider').trigger('destroy.owl.carousel').removeClass('owl-carousel owl-loaded');
            $('.mobile-slider').find('.owl-stage-outer').contents().unwrap();
        }
    });

    initSlider('.good-slider', 3);

    $('.b-type__btn').on('click', function () {
        var owlSlider = $(this).parents('.b-type').find('.good-slider');
        owlSlider.trigger('next.owl.carousel', [300]);
    });

    $('.filter__item').on('click', function () {
        $('.filter__item').removeClass('active');
        $(this).addClass('active');
    });

    $('.accord__open').on('click', function () {
        if ($(this).hasClass('open')){
            $(this).removeClass('open');
            $(this).siblings('.accord__text').slideUp(200);
        } else{
            $('.accord__open').removeClass('open');
            $('.accord__text').slideUp(200);
            $(this).addClass('open');
            $(this).siblings('.accord__text').slideDown(300);
        }
    });

    $('.radio-custom label').on('click', function () {
        var checkImg = $(this).attr('for');
        $('.product__img').removeClass('active');
        $('#img-' + checkImg).addClass('active');
    });

    $('.bank__click').on('click', function(){
        var $DropList = $(this).siblings('.bank__list');
        if ($DropList.is(':visible')){
            $DropList.slideUp(300);
        } else{
            $('.bank__list').slideUp(300);
            $DropList.slideDown(300);
        }
    });

    $(document).click(function (event) {
        if ($(event.target).closest(".bank__drop").length) return;
        $(".bank__list").slideUp(300);
        event.stopPropagation();
    });

    $(document).on('click touchstart', '.ship-radio input', function () {
        $('.ship-radio').parents('tr').removeClass('check');
        $(this).parents('tr').addClass('check');
    });

    $('.btn__to-cart').on('click', function () {
        var $this = $(this)
        $this.siblings('.b-buy__label').addClass('active');
        $this.addClass('hide');
        setTimeout(function () {
            $this.siblings('.b-buy__label').removeClass('active');
            $this.removeClass('hide');
        }, 5000);
    });

    function sendEmail($form) {
        $.ajax({
            url: $form.attr('action'),
            method: $form.attr('method'),
            data: $form.serialize(),
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    $('#js-modal-thank').fadeIn(300);
                    $('.modal-bg').fadeIn(300);

                    setTimeout(function () {
                        $('#js-modal-thank').fadeOut(300);
                        $('.modal-bg').fadeOut(300);
                    }, 5000);
                } else {
                    $('.modal__message').text(data.message);
                    $('#js-modal-add').fadeOut(300);
                    $('#js-modal-error').fadeIn(300);
                    $('.modal-bg').fadeIn(300);

                    setTimeout(function () {
                        $('#js-modal-error').fadeOut(300);
                        $('.modal-bg').fadeOut(300);
                    }, 5000);
                }
            },
            error: function (data) {
                console.log(data);
                alert('Возникла серверная ошибка. Попробуйте еще раз позже либо сообщите администратому. Спасибо.');
            }
        });
    }

    //  CUSTOM SCROLL
    $('.shopcart').mouseover(function(){
        $(".js-scroll-init").mCustomScrollbar({
            axis: "y",
            theme: "dark"
        });
    });

    $('.shopcart').mouseout(function(){
        $(".js-scroll-init").mCustomScrollbar('destroy')
    });
    //  CUSTOM SCROLL

    //CALL MODAL
    $('.js-call-modal').on('click touchstart', function () {
        $('#js-modal-add').fadeIn(300);
        $('.modal-bg').fadeIn(300);
    });
    //CALL MODAL

    //CLOSE MODAL
    $('.modal__close').on('click touchstart', function () {
        $('.modal').fadeOut(300);
        $('.modal-bg').fadeOut(300);
    });
    //CLOSE MODAL

    //SEND FORM MODAL

    $(document).on('submit', '#addReview', function (event) {
        event.preventDefault();

        var $form = $(this);

        $('#js-modal-add').fadeOut(300);

        sendEmail($form);
    });

    $(document).on('submit', '#contact-form', function (event) {
        event.preventDefault();

        var $form = $(this);

        $('#js-modal-add').fadeOut(300);

        sendEmail($form);
    });
    //SEND FORM MODAL

    $('.anchor').on('click', function(){
        var attrVal = $(this).attr('anchor');
        var headerHeight = 0;
        if($(window).width() > 1024){
            headerHeight = 135;
        } else{
            headerHeight = 85;
        }
        $("html, body").animate({ scrollTop: $('#' + attrVal).offset().top - headerHeight }, 600);
    })

    $('.addCheck').on('click',function (event) {
        event.preventDefault();

        $('#js-modal-thank').fadeIn(300);
        $('.modal-bg').fadeIn(300);
    });
});