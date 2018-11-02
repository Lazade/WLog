$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('.operate .submit-btn').click(function(){
    var _this = $(this);
    var thisForm = $(this).parent().parent().parent();
    var formdata = thisForm.serialize();
    console.log(formdata);
    $.ajax({
        url: '/avalon/links/update',
        method: 'POST',
        data: formdata,
        cache: false,
        success: function(result) {
            if (result.status == 'success') {
                _this.parent().parent().parent().css("order", result.info);
            } else {
                alert(result);   
            }
        }
    });
});

$('.operate .destroy-btn').click(function() {
    var _this = $(this);
    var _id = $(this).parent().parent().find('input[name="id"]').val();
    console.log(_id);
    $.ajax({
        url: '/avalon/links/destroy/'+_id,
        method: 'POST',
        cache: false,
        success: function(result) {
            if (result.status == 'success') {
                _this.parent().parent().parent().remove();
            } else {
                alert(result.status);
            }
        },
    });
});