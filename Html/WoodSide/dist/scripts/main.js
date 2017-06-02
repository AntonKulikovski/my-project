$(document).on('ready', function(){
    var href = window.location.href;
    href = href.split('?');
    $('#'+ href[1]).addClass('active');
    $('#tab-' + href[1]).addClass('active');
});
$(document).on('ready', function(){

    if($('#main-slider').length > 0){
        $('#main-slider').owlCarousel({
            pagination: true,
            singleItem: true,
            autoPlay: 10000,
            transitionStyle : "fade",
            afterMove: function(){
                if($(window).width() > 768){
                    $('.slider__plaha').fadeOut(300);
                    $('#plaha-' + this.currentItem).fadeIn(300);
                } else{
                    $('.slider__plaha').hide();
                    $('#plaha-' + this.currentItem).show();
                }
            }
        });
    }

    $('#call-mobile-menu').on('click', function(){
        $('.page-wrapper').toggleClass('open-menu');
        $('.nav__list-wrapper').toggleClass('open-menu');
        $('.header-fix').toggleClass('open-menu');
    });

    $('#close-mobile-menu').on('click', function(){
        $('.page-wrapper').removeClass('open-menu');
        $('.nav__list-wrapper').removeClass('open-menu');
        $('.header-fix').removeClass('open-menu');
        $('.nav__drop-item--composite').find('.nav__drop-list').slideUp(300);
    });

    $(document).mouseup(function (e) {
        var container = $(".nav");
        if (container.has(e.target).length === 0){
            $('.page-wrapper').removeClass('open-menu');
            $('.nav__list-wrapper').removeClass('open-menu');
            $('.header-fix').removeClass('open-menu');
            $('.nav__drop-item--composite').find('.nav__drop-list').slideUp(300);
        }
    });

    $('.nav__item-plus').on('click', function(){
        $(this).siblings('.nav__drop-list--upper').slideToggle(300);
        $(this).parent('.nav__item').toggleClass('open-menu');
        $(this).toggleClass('open-menu');
        if ($(this).html() == '-') {
            $(this).html('+');
        } else {
            $(this).html('-')
        }
    });

    $('.nav__drop-item--composite').on('click', function(){
        $(this).toggleClass('open-menu');
        $(this).find('.nav__drop-list').slideToggle(300);
    });

    $('.js-drop-content').on('click', function(){
        var linkWrap = $(this).parents('.b-hotel');
        var currentHeight = linkWrap.height();
        if(linkWrap.find('.b-hotel__img').is(':visible')){
            linkWrap.find('.b-hotel__img').slideUp(300);
            setTimeout(function(){
                linkWrap.find('.b-hotel__drop-content').slideDown(300);
            },500)
        } else{
            linkWrap.find('.b-hotel__drop-content').slideUp(300);
            setTimeout(function(){
                    linkWrap.find('.b-hotel__img').slideDown(300);
            },400)
        }
    });

    $(window).resize(function(){
        if ($(window).width() > 1024){
            $('.page-wrapper').removeClass('open-menu');
            $('.nav__item').removeClass('open-menu');
            $('.nav__list-wrapper').removeClass('open-menu');
        }
    });

    $('.js-drop-open').on('click', function () {
        var thisList = $(this).siblings('.b-dropdown__list');
        if($(this).parents('.b-dropdown__wrap').hasClass('open')){
            $(this).parents('.b-dropdown__wrap').removeClass('open');
            thisList.slideUp(300);
        } else{
            $('.b-dropdown__wrap').removeClass('open');
            $('.b-dropdown__list').slideUp('300');
            $(this).parents('.b-dropdown__wrap').addClass('open');
            thisList.slideDown(300);
        }
    });

    $('.js-drop-item').on('click', function(){
        var tempItem = $(this).html();
        var parentList = $(this).parents('.b-dropdown__list');
        parentList.siblings('.js-drop-open').html(tempItem);
        parentList.slideUp(300);

        parentList.parents('.b-dropdown__wrap').removeClass('open');
    });

    $('.js-tab-ctrl').on('click', function(){
        var currentId = $(this).attr('id');
        $('.js-tab-ctrl').removeClass('active');
        $(this).addClass('active');
        $('.tab__content').removeClass('active');
        $('#tab-'+ currentId).addClass('active');
    });

    if($(window).width() <= 1024){
        $('.js-call-modal').on('click', function(){
            var modalNum = $(this).attr('id');
            $('#block-' + modalNum).fadeIn(300);
            $('.modal__wrap').fadeIn(300).css('display', 'flex');
        })
    }

    $('.modal__close').on('click', function(){
        $('.js-block-modal').fadeOut(300);
        $('.modal__wrap').fadeOut(300);
    });

    $(document).mouseup(function (e) {
        var container = $(".js-block-modal");
        if (container.has(e.target).length === 0) {
            container.fadeOut(300);
            $('.modal__wrap').fadeOut(300);
        }

        var drop = $(".b-dropdown");
        if (drop.has(e.target).length === 0) {
            $('.b-dropdown__list').slideUp(300);
            $('.b-dropdown__wrap').removeClass('open');
        }
    });

    $('.js-close-map').on('click', function(){
        $('.js-map-wrapper').slideUp(300);
        $('.js-map-open').fadeIn(300);
    });

    $('.js-map-open').on('click', function(){
        $('.js-map-wrapper').slideDown(300);
        $('.js-map-open').fadeOut(300);
    });

    $('.map-point').on('touchstart', function(){
        $('.map-point').removeClass('active');
        $(this).addClass('active');
        var currentId = $(this).attr('id');
        $('.hotel-modal__title').removeClass('active');
        $('.hotel-modal__list').find('.' + currentId).addClass('active');
    });

    $('.js-book-open').on('click', function() {
        $('.m-book').fadeIn(300);
        $('.black-bg').fadeIn(300);
    });

    $('.js-modal-close').on('click', function(){
        $('.m-book').fadeOut(300);
        $('.black-bg').fadeOut(300);
    });

    $(document).mouseup(function (e) {
        var container = $(".m-book");
        if (container.has(e.target).length === 0){
            container.fadeOut(300);
            $('.black-bg').fadeOut(300);
        }
    });

    $( function() {
        if($(window).width() <767){
            var dateFormat = "mm  |  dd  |  yy";
            var dateToday = new Date(),
                from = $("#from")
                    .datepicker({
                        changeMonth: false,
                        numberOfMonths: 1,
                        showOn: "button",
                        buttonImage: "images/ic_calendar-brown.svg",
                        buttonImageOnly: true,
                        constrainInput: true,
                        minDate: dateToday,
                        onSelect: function(selectedDate) {
                            getDate();
                            var option = this.id == "from" ? "minDate" : "maxDate",
                                instance = $(this).data("datepicker"),
                                date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                            from.not(this).datepicker("option", option, date);
                        }
                    })
                    } else{
            var dateFormat = "mm  |  dd  |  yy";
            var dateToday = new Date(),
                from = $("#from")
                    .datepicker({
                        changeMonth: false,
                        numberOfMonths: 2,
                        showOn: "button",
                        buttonImage: "images/ic_calendar-brown.svg",
                        buttonImageOnly: true,
                        constrainInput: true,
                        minDate: dateToday,
                        onSelect: function(selectedDate) {
                            getDate();
                            var option = this.id == "from" ? "minDate" : "maxDate",
                                instance = $(this).data("datepicker"),
                                date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                            console.log(selectedDate);
                            from.not(this).datepicker("option", option, date);
                        }
                    })
        }

        function getDate (){
            var selectDate = $('#from').val();
            var month = selectDate.split('/')[0];
            var day = selectDate.split('/')[1];
            var years = selectDate.split('/')[2];

            $('#fromMonth').val(month);
            $('#fromDay').val(day);
            $('#fromYears').val(years);
        }
    } );

    $('.js-sent-email').on('click', function() {
        $('.js-footer-form').animate({
                opacity : 0
        },300);
        setTimeout(function(){
            $('.js-footer-message').fadeIn(300);
            $('.js-footer-form').css('visibility', 'hidden')
        },300);
    });

    var dateBlock = $('#ui-datepicker-div');
    $('#ui-datepicker-div').remove();
    $('.js-before-datepicker + .m-book__elem').append(dateBlock);

    // $(window).on('scroll', function(){
    //     if ($(window).scrollTop() > 0){
    //         $('.header').addClass('fix');
    //         $(".logo__distin").animate({width:'0'},350);
    //     } else{
    //         $('.header').removeClass('fix');
    //         $(".logo__distin").animate({width:'auto'},350);
    //     }
    // })

    $('.js-scroll-custom').mCustomScrollbar({
        axis:"y",
        theme:"light-3",
        advanced:{autoExpandHorizontalScroll:true}
    });


    // Bold font for safari
    if(navigator.userAgent.indexOf('Mac') > 0){

    } else{
        $('.footer__list a').addClass('bold');
        $('.logo__distin').addClass('bold');
        $('.book-btn__rate').addClass('bold');
        $('.b-link-row__item a').addClass('bold');
        $('.b-link-row__item span').addClass('bold');
        $('.nav__item a').addClass('correctBold');
    }

    var FF = !(window.mozInnerScreenX == null);
    if(FF) {
        // is FireFox
        $('.nav__item a').addClass('correctBold');
    } else {
        // not FireFox
    }

    if( navigator.userAgent.toLowerCase().indexOf('chrome') > -1 ){
        $('.nav__item a').removeClass('correctBold');
    }
    // Bold font for safari


    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1;
    var yyyy = today.getFullYear();

    $('#fromDay').val(dd);
    $('#fromMonth').val('0' + mm);
    $('#fromYears').val(yyyy);


    $('.ui-datepicker-today').addClass('ui-datepicker-current-day');
    $('.ui-datepicker-today a').addClass('ui-state-active');


    function heigthSet(){
        var allTeam = $('.team__item-wrap');
        var maxHeight = 0;
        $(allTeam).each(function() {
            if($(this).height() > maxHeight){
                maxHeight = $(this).height();
            }
        });

        $('.team__item').height(maxHeight - 28);
    }

    heigthSet();

    $(window).resize(function(){
        $('.team__item').height('auto');
        heigthSet();
    });

    // $('.ui-datepicker-trigger').on('click', function(){
    //     $('#ui-datepicker-div').toggleClass('alwaysVisible')
    // });
    //
    // $('.ui-state-default').on('click', function(){
    //     console.log('1');
    // })

});