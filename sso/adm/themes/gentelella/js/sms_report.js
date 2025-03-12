$(document).ready(function () {
    $('#error_wrap').hide();
    $('#success_wrap').hide();
    $('#extra_wrap').hide();


    /* Send MT to MSISDN*/
    $('#button_sentmt').click(function () {

        var btn = $(this);
        btn.button('loading'); // call the loading function
        //Begin Sent  MT
        var msisdn = $('#msisdn').val();
        var item_id = $('#item_id').val();
        var msg_body = $('#msg_body').val();
        var csrf_token = $('#csrf_token').val();
        var data = {'msisdn': msisdn, 'item_id': item_id, 'msg_body': msg_body,'YII_CSRF_TOKEN': csrf_token};
        var loadUrl = 'index.php?r=aSendMT/beginSend';
        $.ajax({
            url: loadUrl,
            dataType: 'json',
            type: 'POST',
            data: data,
            beforeSend:function () {
                $('#success_conent').html('');
                $('#success_desc').html('');
                $('#success_shortcode').html('');
                $('#error_items').html('');
                $('#success_wrap').hide();
                $('#error_wrap').hide();
            },
            success: function (json) {
                btn.button('reset');
                if (json.status == true) {
                    $('#error_wrap').hide();
                    $('#success_conent').html(json.content);
                    $('#success_desc').html(msisdn);
                    $('#success_shortcode').html(item_id);
                    $('#success_wrap').show();
                } else {
                    $('#error_items').html(json.msg);
                    $('#error_wrap').show();
                }

            },
            complete:function () {
                btn.button('reset');
            }
        });
    });

});


function refreshLenght() {
    str_len = $('#msg_body').val().length;
    $('#text_count').html(160 - str_len);
    if (str_len < 0) {
        //$('#msg_body').attr('max-lenght',160)
    }
}

