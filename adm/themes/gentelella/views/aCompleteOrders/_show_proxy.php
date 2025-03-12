<?php if ($model && $type && $order_id && $value):
    ?>
    <div class="modal" id="modal_proxy_<?=$order_id; ?>" role="dialog" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog" style="width: 50%;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Liên lạc với các đầu mối</h4>
                </div>
                <div class="modal-body">
                    <?php $this->widget('booster.widgets.TbGridView', array(
                        'id'            => 'auser-proxy-grid',
                        'dataProvider'  => $model->search_proxy($type),
                        'filter'        => $model,
                        'enableSorting' => FALSE,
                        'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                        'columns'       => array(
                            array(
                                'name'   => 'fullname',
                                'filter' => FALSE,
                                'type'   => 'raw',
                                'value'  => function ($data) {
                                    return CHtml::encode(User::model()->getFullName($data->id));
                                },
                                'htmlOptions' => array('style' => 'width:120px;text-align: left;word-break: break-word;vertical-align:middle;'),
                            ),
                            array(
                                'name'        => 'phone',
                                'filter' => FALSE,
                                'htmlOptions' => array('style' => 'width:120px;text-align: left;word-break: break-word;vertical-align:middle;'),
                            ),
                            array(
                                'name'        => 'email',
                                'filter' => FALSE,
                                'htmlOptions' => array('style' => 'width:120px;text-align: left;word-break: break-word;vertical-align:middle;'),
                            ),

                        ),
                    )); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Close
                    </button>
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