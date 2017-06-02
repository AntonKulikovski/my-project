$(document).ready(function () {
    var $document = $(document);

    $document.on('click', '.deleteProductBasket', function () {
        var $this = $(this);
        var id = $this.closest('li').attr('data-id');
        var li = $this.closest('li');
        var productFirstId = null;
        var productSecondId = null;
        var typePackage = null;

        if ($this.closest('li').attr('data-product') == 'package') {
            typePackage = $this.closest('li').attr('data-typePackage');

            if (li[0].hasAttribute('data-productFirstId')) {
                productFirstId = $this.closest('li').attr('data-productFirstId');
            }
            if (li[0].hasAttribute('data-productSecondId')) {
                productSecondId = $this.closest('li').attr('data-productSecondId');
            }
        }

        var url = '/shopcart/delete/?id=' + id + '&typePackage=' + typePackage + '&productFirstId=' + productFirstId + '&productSecondId=' + productSecondId;

        $.ajax({
            url: url,
            type: 'POST',
            success: function (data) {
                if (data.success) {
                    $this.closest('li').remove();
                    $('.totalSumBasket').attr('data-price', data.totalSum);
                    $('.totalSumBasket').text(number_format(data.totalSum, 2, ',', ' '));
                    $('.shopcart__label').text(data.totalCount);
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