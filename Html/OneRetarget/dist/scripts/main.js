$(document).on('ready', function () {

    $('.js-slider').owlCarousel({
        navigation : false,
        dotsContainer: '.js-slider-dots',
        loop: true,
        items: 1,
        scroll: 1,
        autoplay: true,
        autoplayTimeout: 5000
    });

    var $cntSlider = $(".js-result-slider"),
        $thumbslider = $(".js-thumb-slider");

    $cntSlider.owlCarousel({
            items: 1,
            loop: true,
            mouseDrag  : false
        })

    $thumbslider.owlCarousel({
        items: 3,
        scroll: 1,
        nav: false,
        slideSpeed: 800,
        loop: true,
        center: true,
        touchDrag: false,
        mouseDrag: false,
        responsive: {
            0: {
                items: 1
            },
            1025: {
                items: 3
            }
        }
    });

    $thumbslider.find('.owl-item').on('click', function(){
        if(!$(this).hasClass('center')){
            if($(this).next().hasClass('center')){
                $thumbslider.trigger('prev.owl.carousel', [300]);
                $cntSlider.trigger('prev.owl.carousel', [300]);
            } else{
                $thumbslider.trigger('next.owl.carousel', [300]);
                $cntSlider.trigger('next.owl.carousel', [300]);
            }
        }
        console.log(this);
    });

    $('.js-thumb-prev').click(function () {
        $cntSlider.trigger('prev.owl.carousel', [300]);
        $thumbslider.trigger('prev.owl.carousel', [300]);
    });
    $('.js-thumb-next').click(function () {
        $cntSlider.trigger('next.owl.carousel', [300]);
        $thumbslider.trigger('next.owl.carousel', [300]);
    });

    //    MOBILE MENU

    $(".js-humb").click(function() {
        $(this).toggleClass("on");
        $(".js-nav").toggleClass('open');
        return false;
    });

    $(document).mouseup(function (e) {
        var container = $(".js-header");
        if (container.has(e.target).length === 0){
            $('.js-nav').removeClass('open');
            $('.js-humb').removeClass("on");
        }
    });

//    MOBILE MENU
});