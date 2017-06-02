$(document).ready(function () {
    var $document = $(document);

    $('.ship').find('.check').removeClass('check');

    var tr = $('input[name="Order\[typeDelivery\]"]:checked').closest('tr')[0];

    $(tr).addClass('check');

    var price = Number($('.totalSum').attr('data-price'));
    var priceDelivery = Number($(tr).find('.priceDelivery').attr('data-price'));

    $('.priceTotal').text(number_format(price + priceDelivery, 2, ',', ' '));

    $('.count__btn').on('click', function () {
        var thisCount = $(this).siblings('.count__field');
        var thisCountVal = parseInt(thisCount.val());
        var id = thisCount.closest('tr').attr('data-id');
        var tr = thisCount.closest('tr');
        var productFirstId = null;
        var productSecondId = null;
        var typePackage = null;

        if ($(this).hasClass('count__btn--dec') && (thisCountVal > 1)) {
            thisCount.val(thisCountVal - 1)
        }
        if ($(this).hasClass('count__btn--inc')) {
            thisCount.val(thisCountVal + 1)
        }
        if (thisCount.closest('tr').attr('data-product') == 'package') {
            typePackage = thisCount.closest('tr').attr('data-typePackage');

            if (tr[0].hasAttribute('data-productFirstId')) {
                productFirstId = thisCount.closest('tr').attr('data-productFirstId');
            }
            if (tr[0].hasAttribute('data-productSecondId')) {
                productSecondId = thisCount.closest('tr').attr('data-productSecondId');
            }
        }
        
        updateProductCount(id, thisCount.val(), productFirstId, productSecondId, typePackage);
    });

    function updateProductCount(id, count, productFirstId, productSecondId, typePackage) {
        $.ajax({
            url: '/shopcart/update/?id=' + id + '&count=' + count + '&typePackage=' + typePackage + '&productFirstId=' + productFirstId + '&productSecondId=' + productSecondId,
            method: 'Post',
            data: {
            },
            success: function (data) {
                if (data.success) {
                    if (productFirstId != null && productSecondId != null) {
                        var className = '.sum' + id + '-' + productFirstId + '-' + productSecondId;
                    } else {
                        if (productFirstId == null && productSecondId == null) {
                            var className = '.sum' + id;
                        } else {
                            var className = '.sum' + id + '-' + productFirstId;
                        }
                    }
                    if (Number(data.totalSum) >= 40) {
                        $('.deliveryStandard').text('0,00');
                        $('.deliveryStandard').attr('data-price', 0);
                    } else {
                        $('.deliveryStandard').text('4,00');
                        $('.deliveryStandard').attr('data-price', 4);
                    }

                    $(className).text(number_format(data.sumPrice, 2, ',', ' '));
                    $('.shopcart__label').text(data.totalCount);
                    $('#order-count').val(data.totalCount);
                    $('.totalSum').attr('data-price', data.totalSum);
                    $('.totalSum').text(number_format(data.totalSum, 2, ',', ' '));

                    var priceDelivery = Number($('input[name="Order\[typeDelivery\]"]:checked').closest('tr').find('.priceDelivery').attr('data-price'));

                    $('.priceTotal').text(number_format(Number(data.totalSum) + priceDelivery, 2, ',', ' '));
                }
            },
            error: function (data) {
                alert('Произошла ошибка. Попробуйте позже или обратитесь к администратору.');

                console.log(data);
            }
        });
    }

    $document.on('click', '.deleteProduct', function() {
        if (confirm('Вы действительно хотите удалить этот товар из корзины?')) {
            var $this = $(this);
            var id = $this.closest('tr').attr('data-id');
            var tr = $this.closest('tr');
            var productFirstId = null;
            var productSecondId = null;
            var typePackage = null;

            if ($this.closest('tr').attr('data-product') == 'package') {
                typePackage = $this.closest('tr').attr('data-typePackage');

                if (tr[0].hasAttribute('data-productFirstId')) {
                    productFirstId = $this.closest('tr').attr('data-productFirstId');
                }
                if (tr[0].hasAttribute('data-productSecondId')) {
                    productSecondId = $this.closest('tr').attr('data-productSecondId');
                }
            }

            var url = '/shopcart/delete/?id=' + id + '&productFirstId=' + productFirstId + '&productSecondId=' + productSecondId + '&typePackage=' + typePackage;

            $.ajax({
                url: url,
                type: 'POST',
                success: function (data) {
                    if (data.success) {
                        $this.closest('tr').remove();
                        $('.totalSum').attr('data-price', data.totalSum);
                        $('.totalSum').text(number_format(data.totalSum, 2, ',', ' '));
                        $('.shopcart__label').text(data.totalCount);
                        $('#order-count').val(data.totalCount);
                        
                        if (Number(data.totalSum) >= 40) {
                            $('.deliveryStandard').text('0,00');
                            $('.deliveryStandard').attr('data-price', 0);
                        } else {
                            $('.deliveryStandard').text('4,00');
                            $('.deliveryStandard').attr('data-price', 4);
                        }
                        if (data.emptyShopcart) {
                            $('.b-type').hide();
                            $('.emptyShopcart').show();
                        }

                        var priceDelivery = Number($('input[name="Order\[typeDelivery\]"]:checked').closest('tr').find('.priceDelivery').attr('data-price'));

                        $('.priceTotal').text(number_format(Number(data.totalSum) + priceDelivery, 2, ',', ' '));
                    } else {
                        alert([].pop.apply(data.errors));
                    }
                },
                error: function (data) {
                    console.log(data);
                    alert('Возникла серверная ошибка. Попробуйте еще раз позже либо сообщите администратому. Спасибо.');
                }
            });
        }

        return false;
    });

    $('input[name="Order\[typeDelivery\]"]').change(function() {
        var price = Number($('.totalSum').attr('data-price'));
        var priceDelivery = Number($(this).closest('tr').find('.priceDelivery').attr('data-price'));
        $('.priceTotal').text(number_format(price + priceDelivery, 2, ',', ' '));
    });

    $('input[name="Order\[typePayment\]"]').change(function() {
        if ($('input[name="Order\[typePayment\]"]:checked').val() == 'BANK_CARD_ON_LINE' ||
            $('input[name="Order\[typePayment\]"]:checked').val() == 'ERIP') {
            $('.info_hide').show();
        } else {
            $('.info_hide').hide();
        }
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

    if ($('input[name="Order\[typePayment\]"]:checked').val() == 'BANK_CARD_ON_LINE' ||
        $('input[name="Order\[typePayment\]"]:checked').val() == 'ERIP') {
        $('.info_hide').show();
    } else {
        $('.info_hide').hide();
    }
});