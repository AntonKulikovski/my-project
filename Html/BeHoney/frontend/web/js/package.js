$(document).ready(function () {
    function ajax(url, slug) {
        $.ajax({
            url: url,
            method: 'Get',
            data: {
            },
            success: function (data) {
                if (data.success) {
                    history.pushState(null, null, '/podarochnye-nabory/' + slug + '/');
                    $('.b-type__cnt').html(data.packageView);
                    $('.b-type__title').html(data.tagName);
                    $('.breadcrumb').html(data.breadcrumbs);
                }
            },
            error: function (data) {
                alert('Произошла ошибка. Попробуйте позже или обратитесь к администратору.');

                console.log(data);
            }
        });
    }

    $('.filter__item').on('click', function(){
        var slug = $(this).attr('data-slug');
        var url = '/podarochnye-nabory/tag/' + slug + '/';

        $('.filter__item.active').removeClass('active');;
        $(this).toggleClass('active');

        ajax(url, slug);
    });
});