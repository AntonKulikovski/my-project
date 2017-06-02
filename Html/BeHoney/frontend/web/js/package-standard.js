$(document).ready(function () {
    var $document = $(document);

    $(document).on("click" , '.bank__item', function () {
        var $this = $(this);
        var name = $this.html();
        var id = $this.attr('data-id');
        var price = Number($('.priceList [data-id=' + id + ']').text());

        if ($this.parents('.bank__drop').find('.bank__select').hasClass('firstDropList')) {
            $('.imageFirst.active').removeClass('active');
            $('.imageFirst[set-img=' + id + ']').addClass('active');
            $('.product__cost').attr('data-priceProductFirst', price);
            $('input[name="AddToCartForm\[productFirstId\]"]').val(id);
        } else {
            $('.imageSecond.active').removeClass('active');
            $('.imageSecond[set-img=' + id + ']').addClass('active');
            $('.product__cost').attr('data-priceProductSecond', price);
            $('input[name="AddToCartForm\[productSecondId\]"]').val(id);
        }

        $this.parents('.bank__drop').find('.bank__select').val(name);
        $this.parents(".bank__list").slideUp(300);
        
        pricePackage();
    } );

    $('.count__btn').on('click', function(){
        var thisCount = $(this).siblings('.count__field');
        var thisCountVal = parseInt(thisCount.val());

        if($(this).hasClass('count__btn--dec') && (thisCountVal > 1)){
            thisCount.val(thisCountVal - 1)
        }
        if($(this).hasClass('count__btn--inc')){
            thisCount.val(thisCountVal + 1)
        }

        pricePackage();
    });
    
    function pricePackage() {
        var priceProductFirst = Number($('.product__cost').attr('data-priceProductFirst'));
        var priceProductSecond = Number($('.product__cost').attr('data-priceProductSecond'));
        var pricePackage = Number($('.product__cost').attr('data-pricePackage'));
        var countPackages = Number($('.count__field').val());
        var price = (priceProductFirst + priceProductSecond + pricePackage) * countPackages;
        $('.packagePrice').text(number_format(price, 2, ',', ' '));
    }

    $document.on('submit', '#addToShopcartForm', function () {
        var $form = $(this);

        if($(window).width() > 768){

            var positionImgLeft = $('.js-img-move').offset();
            $('.js-img-move').clone().appendTo('.js-image-wrap');
            $('.js-image-wrap').css({
                left: positionImgLeft.left,
                top: positionImgLeft.top,
                opacity: 1
            });

            $('.js-image-wrap img').css({
                width: 'auto'
            });

            var positionBasket = 0;
            var sizeBasket = 0;
            if ($(window).width() > 1024){
                positionBasket = $('.shopcart-desktop').position();
                sizeBasket = $('.shopcart-desktop').width()
            }
            else{
                positionBasket = $('.shopcart--tablet').position()
            }

            $('.js-image-wrap').animate({
                left: positionBasket['left'],
                top: positionBasket['top'],
                opacity: 0
            },500);

            $('.js-image-wrap img').animate({
                width: sizeBasket
            },500, function(){
                $('.js-image-wrap img').remove();
            });

        }

        $.ajax({
            url: $form.attr('action'),
            method: $form.attr('method'),
            data: $form.serialize(),
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    $('.shopcart__label').text(data.totalCount);
                    $('.js-basket').html(data.basket);
                    $('.totalSumBasket').attr('data-price', data.totalSum);
                    $('.totalSumBasket').text(number_format(data.totalSum, 2, ',', ' '));
                } else {
                    alert([].pop.apply(data.errors));
                }
            },
            error: function (data) {
                console.log(data);
                alert('Возникла серверная ошибка. Попробуйте еще раз позже либо сообщите администратому. Спасибо.');
            }
        });

        return false;
    });

    function number_format( number, decimals, dec_point, thousands_sep ) {	// Format a number with grouped thousands
        //
        // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
        // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // +	 bugfix by: Michael White (http://crestidg.com)

        var i, j, kw, kd, km;

        // input sanitation & defaults
        if( isNaN(decimals = Math.abs(decimals)) ){
            decimals = 2;
        }
        if( dec_point == undefined ){
            dec_point = ",";
        }
        if( thousands_sep == undefined ){
            thousands_sep = ".";
        }

        i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

        if( (j = i.length) > 3 ){
            j = j % 3;
        } else{
            j = 0;
        }

        km = (j ? i.substr(0, j) + thousands_sep : "");
        kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
        //kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
        kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");

        return km + kw + kd;
    }
});