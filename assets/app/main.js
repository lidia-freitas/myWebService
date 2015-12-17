'use strict';

function addProduct() {
    var postRoute = 'http://localhost:8000/productAdd';
    $.ajax({
        type: 'POST',
        contentType: 'application/json',
        url: postRoute,
        dataType: "json",
        data: formToJSON(),
        success: function(data){
            if (!data.success) {
                if (data.errors) {
                    if (data.errors[0] === 'database') {
                        $('.alert-danger').fadeIn(1000).append('<p>' + data.errors[1] + '</p>');
                        console.log(data.errors[2]);
                    }else{
                        $('.has-error').removeClass('has-error');
                        for (var fields in data.errors) {
                            if (data.errors.hasOwnProperty(fields)) {
                                $(fields).parent().addClass('has-error');
                                $(fields).next().text(data.errors[fields]);
                                console.log(data.errors[fields]);
                            }
                        }
                    }
                }
            }else {
                $('.has-error').removeClass('has-error');
                $('.help-block').text('');
                $('.alert-success').fadeIn(1000).append('<p>' + data.posted + '</p>');
                $('#field-name').val('');
                $('#field-price').val('');
                $('#field-inclusion').val('');
            }
        }
    });
}

function formToJSON() {
    return JSON.stringify({
        "name": $('#field-name').val(),
        "price": $('#field-price').val(),
        "inclusion": $('#field-inclusion').val()
    });
}

$('.alert .close').on('click', function () {
    $(this).parent().fadeOut();
});
