<?php if ($order_id && $sim):
    ?>
    <div class="modal" id="modal_roaming_<?php echo $order_id; ?>" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">

                    <ul class="nav nav-pills" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#qrcode" aria-controls="home" role="tab" data-toggle="tab">B1: Quét QR CODE</a>
                        </li>
                        <li role="presentation">
                            <a href="#check_roaming" aria-controls="profile" role="tab" data-toggle="tab">B2: Kiểm tra hòa mạng</a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        <div role="tabpanel" class="tab-pane active" id="qrcode">

                            <div class="text-center">
                                <h5>Quét QR CODE</h5>
                                <?php $this->renderPartial('_popup_esim_qrcode', array(
                                    'order'     => $order,
                                    'sim'       => $sim,
                                    'package'   => $package,
                                    'shipper'   => $shipper,
                                    'user'      => $user,
                                    'modal'     => false,
                                ));?>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="check_roaming">
                            <?php $form = $this->beginWidget('CActiveForm', array(
                                'id'                   => 'check-roaming-form',
                                'action'               => Yii::app()->createAbsoluteUrl("aCompleteOrders/checkRoaming"),
                                // Please note: When you enable ajax validation, make sure the corresponding
                                // controller action is handling ajax validation correctly.
                                // There is a call to performAjaxValidation() commented in generated controller code.
                                // See class documentation of CActiveForm for details on this.
                                'enableAjaxValidation' => TRUE,
                            )); ?>
                            <div class="row" style="text-align: center;">
                                <h5>Khai báo sim thành công! Vui lòng kiểm tra hòa mạng!</h5>
                                <div class="row" style="margin-top: 20px;">
                                    <?php echo CHtml::button('Kiểm tra hòa mạng',
                                        array("onclick" => "check_roaming('$order_id');",
                                            "id"      => "roaming_$order_id",
                                            "class"   => "btn btn-success")); ?>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="errorMessage" id="error_roaming_number" style="display:none"></div>
                                </div>
                            </div>
                            <?php $this->endWidget(); ?>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default close-button" data-dismiss="modal">
                        OK
                    </button>
                </div>
            </div>

        </div>
    </div>


<?php endif; ?>
<style>
    #error_serial_number {
        margin-top: 10px;
    }

    .close {
        margin-top: -10px !important;
    }
</style>
<script type="text/javascript">
    $('.close').click(function () {
        $('.modal-backdrop').remove();
    });
    $('.close-button').click(function () {
        $('.modal-backdrop').remove();
    });
    function check_roaming(order_id) {
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
        var serial_number = $('#serial_number').val();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: '<?=Yii::app()->createUrl('aCompleteOrders/checkRoaming')?>',
            crossDomain: true,
            data: {
                order_id: '<?=$order_id?>',
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (data) {
                $('.modal-backdrop').remove();
                $('#error_roaming_number').html(data.msg);
                $('#error_roaming_number').css("display", "inline");
                $('.loading-div').removeAttr("style");
                $('.loading-div').css("display", "none");
                if (data.success == true) {
                    window.location.reload();
                }
            }
        });
    }
</script>