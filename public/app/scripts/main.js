let $ = require('jquery');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#home-slide').click(function(){
    if ($(this).hasClass('slide')) {
        $(this).removeClass('slide');
        $('body, html').removeClass('sliding');
        $('.wlog-app-nav').removeClass('showing');
    } else {
        $(this).addClass('slide');
        $('body, html').addClass('sliding');
        $('.wlog-app-nav').addClass('showing');
    }
});

$('.cover-shelt').click(function(){
    $('#home-slide').removeClass('slide');
    $('body, html').removeClass('sliding');
    $('.wlog-app-nav').removeClass('showing');
});