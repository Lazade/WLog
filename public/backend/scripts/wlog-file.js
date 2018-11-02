// ui - part
$('.wlog-info-button').click(function() {
    $('.wlog-floating-area .wlog-floating').hide(100);
    if ($(this).hasClass('focus')) {
        $(this).removeClass('focus');
    } else {
        $(this).addClass('focus');
        $('.wlog-floating-area .wlog-info-table').show();
    }
});

$('.wlog-upload-button').click(function() {
    $('.wlog-floating-area .wlog-floating').hide(100);
    if ($(this).hasClass('focus')) {
        $(this).removeClass('focus');
    } else {
        $(this).addClass('focus');
        $('.wlog-floating-area .wlog-upload-form').show();
    }
});

$('.wlog-delete-option-button').click(showDeleteOption);

function showDeleteOption() {
    var self = $(this);
    if (self.hasClass('focus')) {
        $('.file_checkbox').remove();
        $('.wlog-delete-confirm').hide();
        self.removeClass('focus');
    } else {
        self.addClass('focus');
        $('.wlog-img__list .wlog-img__item').each(function () {
            var file_checkbox = document.createElement('input');
            $(file_checkbox).attr("type", "checkbox");
            $(file_checkbox).attr("name", "id[]");
            $(file_checkbox).attr("value", $(this).find('img').attr('id_value'));
            $(file_checkbox).addClass('file_checkbox');
            $(this).append(file_checkbox);
        });
        $('.wlog-delete-confirm').css('display', 'inline-flex');
    }
}

// 
function base64Encode(input) {
    var rv;
    rv = encodeURIComponent(input);
    rv = unescape(rv);
    rv = window.btoa(rv);
    return rv;
}

function base64Decode(input) {
    rv = window.atob(input);
    rv = escape(rv);
    rv = decodeURIComponent(rv);
    return rv;
}

function getName(array) {
    var arr = new Array();
    for (var i in array) {
        arr.push(array[i]['name'])
    }
    return arr;
}

function fileDetect(files) {
    var namesList = new Array();
    for (var i in files) {
        if (typeof(files[i]) != 'object') {
            continue;
        }
        if ( /image\/\w+/.test(files[i].type) ) {
            namesList.push(files[i].name);
        } else {
            return false;
        }
    }
    return namesList;
}

function cleanResultList() {
    $('#info-table').html('');
}

function showResultList(list) {
    $('.wlog-info-button').removeClass('focues');
    $('.wlog-floating-area .wlog-info-table').hide();
    var html = '<ul>';
    for (var i in list) {
        var color = list[i].head_state == 200 ? 'green' : 'red';
        html += '<li><span class="file-name">'+list[i].file+'</span><span class="status" style="color: '+color+'">'+list[i].status+'</span></li>';
    }
    html += '</ul>';
    $('#info-table').append(html);
    $('.wlog-info-button').addClass('focus');
    $('.wlog-floating-area .wlog-info-table').show();
}

function showAlert(info, status = 'info') {
    var cname = '';
    switch(status) {
        case 'success': cname = 'alert-info'; break;
        default: cname = 'alert-danger';
    }
    $('.wlog-log-area .alert').html(info);
    $('.wlog-log-area .alert').addClass(cname).show();
    setTimeout(function (){
        $('.wlog-log-area .alert').attr('class', '.alert').html('').hide();
    },3000);
}

// ajax - part

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#file').on('change', function() {
    if ($('#file').val() == '') return;
    var path = $('#file')[0].files;
    var ret = fileDetect(path);
    if (!ret) {
        alert('File type or size is Incompliant. Choose Again.');
        return;
    } 
    var formData = new FormData();
    var names = '';
    for ( var i = 0, name; i < path.length; i++ ) {
        name = $.md5(path[i].name);
        formData.append(name, path[i]);
        names += name + ',';
    }
    formData.append('info', names);
    $.ajax({
        url: '/avalon/file',
        type: 'POST',
        cache: false,
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
            $('#progress').show('slow');
            cleanResultList();
        },
        success: function(result) {
            $('.wlog-upload-button').removeClass('focus');
            $('.wlog-floating-area .wlog-floating').hide(100);
            if (result.status == 200) {
                for (var i in result.info) {
                    if (result.info[i].head_state == 200) {
                        var div = '<div class="layout-img__item wlog-img__item"><div class="item__inner"><div class="img-container"><img src="'+result.info[i].url+'" id_value="'+result.info[i].id+'"></div><div class="info"><p class="info-name">'+result.info[i].file+'</p><p class="info-date">'+result.info[i].date+'</p></div></div></div>';
                        $('.wlog-img__list').append(div);
                    }
                }
                showResultList(result.info);
            } else {
                alert(result.errors.info);
            }
        },
        complete: function() {
            $(this).parent().removeClass('disabled');
            $('#progress').hide(100);
        },
    });
});

$('.wlog-delete-confirm').click(function() {

    var formData = new FormData();
    var file_list = '';
    var file_checkbox = $('.file_checkbox:checked');
    $('.file_checkbox:checked').each(function(){
        file_list += $(this).val() + ',';
    });
    formData.append('id', file_list);
    $.ajax({
        url: '/avalon/file/destroy',
        type: 'POST',
        cache: false,
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
            cleanResultList();
        },
        success: function(result) {
            for (var i in result.info) {
                if (result.info[i].head_state == 200) {
                    file_checkbox.each(function() {
                        if ($(this).val() == result.info[i].id) {
                            $(this).parent().remove();
                        }
                    });
                }
            }
            showResultList(result.info)
        },
        complete: function() {
            $('.wlog-delete-option-button').trigger('click');
        },
    });

});