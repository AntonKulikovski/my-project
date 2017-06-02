$(document).on('ready', function(){
    var href = window.location.href;
    href = href.split('?');
    $('#'+ href[1]).addClass('active');
    $('#tab-' + href[1]).addClass('active');
});