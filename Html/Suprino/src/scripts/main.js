$(function(){

    'use strict';

    $('.js-localization__select').click(function(){
        $(this).parents('.localization').find('.js-localization__drop').slideToggle(300);
    });

    $('.js-localization__drop-item').click(function(){
        var tempVar = $(this).html();
        $('.js-localization__select').html(tempVar);
        $('.js-localization__drop').slideUp(300);

        $('.js-localization__drop-item').removeClass('active');
        var langAttr = $(this).attr('langAttr');
        $('.js-localization-' + langAttr).addClass('active');
    });

    $(document).mouseup(function (e) {
        var container = $(".localization");
        if (container.has(e.target).length === 0){
            container.find('.js-localization__drop').slideUp(300);
        }
    });

    $('.js-localization__drop-item').click(function(){
        if($(window).width() < 1199){
            var tempVarMob = $(this).html();
            $('.js-localization__select').html(tempVarMob);
            $('.js-localization__drop-item').removeClass('active');
            var langAttr = $(this).attr('langAttr');
            $('.js-localization-' + langAttr).addClass('active');
        }
    });

    $('#banner-slider').carouFredSel({
        width: '100%',
        height: 'variable',

        swipe: true,
        responsive: true,
        circular: false,
        auto: false,
        prev    :{
            button  :".slider__arrow--prev"
        },
        next    :{
            button  :".slider__arrow--next"
        },
        items    : {
            visible: 1,
            height: 'variable'
        },
        scroll : {
            fx : "crossfade",
            onBefore    : function( data){
                var currentAttr =data.items.visible.attr('id');
                $('.b-about__item').removeClass('active');
                $('.js-' + currentAttr).addClass('active');
            },
            onAfter     : function( data ) {
                data.items.visible.addClass('active');
                data.items.old.removeClass('active');
            }
        }
    });

    $('#thumbs li').click(function() {
        $('#banner-slider').trigger('slideTo', '#' + $(this).attr('thumb').split('#').pop() );
        $('#thumbs li').removeClass('active');
        $(this).addClass('active');
        return false;
    });

    $('.anchor').click(function(){
        var thisAttr = $(this).attr('scrollLink');
        var thisScroll = $('#anchor-' + thisAttr).offset().top;
        $("html, body").animate({ scrollTop: thisScroll }, 1000);
    });

    function aboutBlockAlign(){
        $('.b-about__item').height('auto');
        var aboutHeight = $('.b-about__list').height();
        $('.b-about__item').height(aboutHeight);
    }

    aboutBlockAlign();
    $(window).resize(aboutBlockAlign);

    $('.header__mob-menu').click(function(){
        $('.js-drop-menu').slideToggle(300);
    });

    $('#slide-1').addClass('active');

    function menuScale(){
        var scrollPosition = $('#anchor-js-sentence').offset().top;
        if($(window).scrollTop() >= scrollPosition){
            $('.header .c-align').css('transform', 'scale(0.9)');
            $('.header .c-align').css('-ms-transform', 'scale(0.9)');
            $('.header .c-align').css('-webkit-transform', 'scale(0.9)');
        }
        else{
            $('.header .c-align').css('transform', 'scale(1)');
            $('.header .c-align').css('-ms-transform', 'scale(1)');
            $('.header .c-align').css('-webkit-transform', 'scale(1)');
        }
    }

    $(window).scroll(menuScale);

    $(window).enllax();
});

jQuery(document).ready(function(){
    // Declare parallax on layers
    jQuery('.parallax-layer').parallax({
        mouseport: jQuery("#port"),
        decay: 0.1,
        xorigin: 0.3,
        yorigin: false,
        xparallax: 0.3,
        yparallax: false
    });
});