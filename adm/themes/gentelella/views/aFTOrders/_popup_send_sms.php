<?php if ($model):

    ?>
    <div class="modal" id="modal_send_sms_<?php echo $model->id; ?>" role="dialog" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog" style="width: 50%;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-header">
                    <h5> Gửi tin nhắn tới khách hàng</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h5> + Họ tên khách hàng : <span style="color: red;"><?= $model->orderer_name ?> </span></h5>
                    <h5> + Số điện thoại gửi : <span style="color: red;"><?= $model->orderer_phone; ?></span></h5>

                    <div class="form" id="send_sms">
                        <div class="form-group">
                            <label style="margin-top: 10px;">Nội dung tin nhắn:</label>
                            <textarea rows="5" cols="100" name="AFTSms[content]" id="AFTSms_content"></textarea>
                            <div class="errorMessage" id="AFTSms_content_error_" style="margin-top:15px;"></div>
                        </div>
                        <input type="hidden" name="AFTSms[msisdn]" id="AFTSms_msisdn"
                               value="<?= $model->orderer_phone ?>">
                    </div>
                    <div class="modal-footer">
                        <?php echo CHtml::submitButton('Gửi',
                            array("onclick" => "sendSms('$model->code');",

                                  "class" => "btn btn-success")); ?>

                        <button type="button" class="btn btn-default close-button" data-dismiss="modal">
                            Hủy bỏ
                        </button>
                    </div>
                </div><!-- form -->
            </div>

        </div>

    </div>

<?php endif; ?>
<style>
    .close {
        margin-top: -10px !important;
    }

    #send_sms textarea {
        width: auto;
        height: auto;
    }
</style>
<script type="text/javascript">
    // Xác thực mã otp
    $('.close').click(function () {
        $('.modal-backdrop').remove();
    });
    $('.close-button').click(function () {
        $('.modal-backdrop').remove();
    });


    function sendSms(order_code) {
        var msisdn = $('#AFTSms_msisdn').val();
        var content = $('#AFTSms_content').val();

        if (content == '') {
            $('#AFTSms_content_error_').html("Nội dung không được phép rỗng!");
        } else {


            $('#AFTSms_content_error_').html("");
            $('.loading-div').css({
                "float": "left",
                "width": "100%",
                "height": "100%",
                "z-index": "999999",
                "position": "absolute",
                "text-align": "center"
            });
            $('.loading-div').html("<img style='text-align:center; width:24px; height:24px; margin-top:10%; z-index:999999;'  " +
                "src='<?=Yii::app()->theme->baseUrl;?>/images/loading.gif'/>").fadeIn();
            setTimeout(function () {
                alert("Có lỗi xảy ra vui lòng thử lại!");
                window.location.reload();
            }, 10000);
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->createUrl('aFTOrders/sendSMS')?>',
                crossDomain: true,
                dataType: 'json',
                data: {
                    content: content,
                    order_code: order_code,
                    msisdn: msisdn,
                    'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
                },
                success: function (result) {
                    if (result === true) {
                        alert("Gửi thành công!");
                        window.location.reload();
                        return true;
                    }
                }
            });
        }
    }
</script>

