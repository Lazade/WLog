// var $ = require('jquery');

$(document).ready(function() {
    wlogTabInit();
});

$('#sidebarToggle').click(function() {
    if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        $('#wlog-sidebar').css('right', '-320px');
    } else {
        $(this).addClass('active');
        $('#wlog-sidebar').css('right', '0px');
    }
});

$('#wlog-tab-bar .wlog-tab').click(function() {
    let width = $('#wlog-tab-bar .wlog-tab:eq(0)')[0].clientWidth;
    let panelWidth = $('#wlog-tab-panels')[0].clientWidth;
    let index = $(this).index();
    let move = index * width;
    let innerMove = - (index * panelWidth);
    $(this).addClass('wlog-tab--active').siblings('#wlog-tab-bar .wlog-tab').removeClass('wlog-tab--active');
    $('#wlog-tab-bar .layout-tab-bar__indicator').animate({'left': move+'px'}, 100);
    $('#wlog-tab-panels .layout-tab-panels__container').css('transform', 'translate3D('+innerMove+'px, 0, 0)');
});

$('.wlog-cus-select label').click(function() {
    if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        $(this).parent().find('.select').hide(100);
    } else {
        $(this).addClass('active');
        $(this).parent().find('.select').show(200);
    }
});

$('.wlog-cus-select .select li').click(function(){
    $(this).parent().parent().find('input').val($(this).attr('data'))
    $(this).parent().parent().find('.default').attr('class', $(this).find('i').attr('class')+' default')
    $(this).parent().parent().find('.select').hide(100);
});

$('.wlog-message-toggle').click(function() {
    $('.wlog-message').toggleClass('showing');
});

$('.wlog-alert button').click(function() {
    $('.wlog-alert').hide();
});

// wlogTabInitial
function wlogTabInit() {
    let length = $('#wlog-tab-bar .wlog-tab').length;
    if (length == 0 ) {
        return;
    } else {
        // let eachWidth = ;
        $('#wlog-tab-bar .wlog-tab').css('width', (100 / length)+'%');
    }
    
    let tabWidth = $('#wlog-tab-bar .wlog-tab:eq(0)')[0].clientWidth;
    $('#wlog-tab-bar .layout-tab-bar__indicator').css('width', tabWidth+'px');

    // initialize panel-slider
    let panelWidth = $('#wlog-tab-panels')[0].clientWidth;
    $('#wlog-tab-panels .wlog-tab-panel').each(function() {
        var div = document.createElement('div');
        $(div).addClass('slide');
        $(div).css({'width': panelWidth+'px'});
        $(this).wrap(div);
    });
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('.wlog-get-action').click(function() {
    var url = $(this).attr('data-url');
    $.ajax({
        url: url,
        type: 'GET',
        beforeSend: function() {
            $('.wlog-message .message__inner').html('');
        },
        success: function(result) {
            var span = '<span>'+result.message+'</span>';
            $('.wlog-message .message__inner').append(span);
            $('.wlog-message').addClass('showing');
        }
    })
});

$('.wlog-delete-action').click(function() {
    var confirmMsg = confirm('Are You Sure ?');
    if (confirmMsg == true) {
        var data = $(this).parent().serialize();
        var url = $(this).parent()[0].action;
        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            beforeSend: function() {
                $('.wlog-message .message__inner').html('');
            },
            success: function(result) {
                var span = '<span>'+result.message+'</span>';
                $('.wlog-message .message__inner').append(span);
                $('.wlog-message').addClass('showing');
                $('#'+result.delteID).remove();
            }
        })
    }
});

$('.wlog-change-state').click(function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    var iTag = $(this).find('i');
    $.ajax({
        url: url,
        type: 'GET',
        beforeSend: function() {
            $('.wlog-message .message__inner').html('');
        },
        success: function(result) {
            if (result.status) {
                iTag.attr('class', 'fas fa-eye');
            } else {
                iTag.attr('class', 'fas fa-eye-slash')
            }
            var span = '<span>'+result.message+'</span>';
            $('.wlog-message .message__inner').append(span);
            $('.wlog-message').addClass('showing');
        }
    })
});


