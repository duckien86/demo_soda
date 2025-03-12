<?php if ($otp_form):
    ?>
    <div class="modal" id="modal_<?php echo $otp_form->order_id; ?>" role="dialog" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog" style="width: 50%;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Xác thực đơn hàng</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="">
                            <ul style="list-style: disc">
                                <li>
                                    <b>Bước 1: </b>Bấm nút "Nhận mã xác thực" dưới đây<br/>
                                    <div class="form-group">
                                        <?php echo CHtml::button('Nhận mã xác thực',
                                            array("onclick" => "resend_otp('$otp_form->order_id');",
                                                "id"      => "$otp_form->order_id",
                                                "class"   => "btn btn-xs btn-danger")); ?>
                                    </div>

                                </li>
                                <li>
                                    <b>Bước 2: </b>Nhập mã xác thực khách hàng vừa nhận trong tin nhắn<br/>
                                    <div class="form-group">
                                        <div class="row">
                                            <input size="60" maxlength="100" readonly="1" name="AOtpForm[order_id]" id="AOtpForm_order_id"
                                                   type="hidden" value="<?php echo $otp_form->order_id ?>">
                                            <div class="errorMessage" id="AOtpForm_order_id_em_" style="display:none"></div>
                                        </div>
                                        <div class="row" style="max-width: 400px; width: 100%">
<!--                                            <label for="AOtpForm_otp" class="required">Mã xác nhận <span class="required">*</span></label>-->
                                            <input size="50" maxlength="100" class="form-control" name="AOtpForm[otp]" id="AOtpForm_otp"
                                                   type="text">
                                            <div class="errorMessage" id="AOtpForm_otp_em_" style="display:none"></div>
                                        </div>
                                    </div>
                                </li>


                                <li>
                                    <b>Bước 3: </b>Bấm nút Xác thực<br/>


                                </li>
                            </ul>
                        </div>
                    </div>



                </div>
                <div class="modal-footer">
                    <?php echo CHtml::button('Xác thực',
                        array("onclick" => "check_otp('$otp_form->order_id');",
                              "id"      => "$otp_form->order_id",
                              "class"   => "btn btn-success")); ?>

                </div>
            </div>

        </div>
    </div>


<?php endif; ?>
<style>
    .close {
        margin-top: -10px !important;
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

    function check_otp(order_id) {

        if ($('#AOtpForm_otp').val() == '') {
            $('#AOtpForm_otp_em_').text("Mã xác thực nhập ko được phép rỗng!");
            $('#AOtpForm_otp_em_').css("display", "block");
        } else {
            $('.loading-div').css({
                "float": "left",
                "width": "100%",
                "height": "100%",
                "z-index": "999999",
                "position": "absolute",
                "text-align": "center"
            });
            $('.loading-div').html("<img style='text-align:center; width:24px; height:24px; margin-top:10%; z-index:999999;'  src='<?=Yii::app()->theme->baseUrl;?>/images/loading.gif'/>").fadeIn();
            var otp = $('#AOtpForm_otp').val();
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->createUrl('aCompleteOrders/checkOtpExist')?>',
                crossDomain: true,
                data: {
                    order_id: order_id,
                    otp: otp,
                    'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
                },
                success: function (data) {
                    if (data == 0) {
                        $('#AOtpForm_otp_em_').text("Mã xác thực nhập ko đúng!");
                        $('#AOtpForm_otp_em_').css("display", "block");
                        $('.loading-div').removeAttr("style");
                        $('.loading-div').css("display", "none");
                    } else {
                        $.ajax({
                            type: "POST",
                            url: '<?=Yii::app()->createUrl('aCompleteOrders/checkSerialSim')?>',
                            crossDomain: true,
                            data: {
                                order_id: order_id,
                                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
                            },
                            success: function (data) {
                                $('.popup_data').hide();
                                $('.modal-backdrop').remove();
                                $('.popup_data_serial').children().remove("div");
                                $('.popup_data_serial').append(data);
                                var modal_id = 'modal_serial_' + order_id;
                                $('#' + modal_id).modal('show');
                                return false;
                            }
                        });
                    }
                    return true;
                }
            });
        }
    }

    // Gửi lại mã otp
    function resend_otp(order_id) {
        $('.loading-div').css({
            "float": "left",
            "width": "100%",
            "height": "100%",
            "z-index": "999999",
            "position": "absolute",
            "text-align": "center"
        });
        $('.loading-div').html("<img style='text-align:center; width:24px; height:24px; margin-top:10%; z-index:999999;'  src='<?=Yii::app()->theme->baseUrl;?>/images/loading.gif'/>").fadeIn();
        $.ajax({
            type: "POST",
            url: '<?=Yii::app()->createUrl('aCompleteOrders/resendOtp')?>',
            crossDomain: true,
            data: {
                order_id: order_id,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (data) {
                data = jQuery.parseJSON(data);
                $('.loading-div').removeAttr("style");
                $('.loading-div').css("display", "none");
                alert(data.msg);
            }
        });
    }
</script>