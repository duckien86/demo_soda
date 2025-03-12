<?php if ($order_id):
    ?>
    <div class="modal" id="modal_serial_<?php echo $order_id; ?>" role="dialog" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog" style="width: 50%;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row" style="width: 65%;margin: auto;">
                        <label class="required">Khai báo sim</label>
                        <input size="50" maxlength="100" class="form-control" name="serial_number" id="serial_number"
                               type="text" placeholder="Nhập 10 số serial trên Sim">
                        <div class="errorMessage" id="error_serial_number" style="display:none"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php echo CHtml::button('Khai báo',
                        array("onclick" => "check_serial_number('$order_id');",
                              "id"      => "serial_$order_id",
                              "class"   => "btn btn-success")); ?>
                    <button type="button" class="btn btn-default close-button" data-dismiss="modal">
                        Hủy
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
    function check_serial_number(order_id) {
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
        var phone_customer = <?=$phone_customer?>;

        $.ajax({
            type: "POST",
            dataType: 'json',
            url: '<?=Yii::app()->createUrl('aCompleteOrders/callApiRegisterSim')?>',
            crossDomain: true,
            data: {
                serial_number: serial_number,
                phone_customer: '<?=$phone_customer?>',
                order_id: '<?=$order_id?>',
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (data) {
                if (data.success == false) {
                    $('#error_serial_number').text(data.msg);
                    $('#error_serial_number').css("display", "block");

                    $('.loading-div').removeAttr("style");
                    $('.loading-div').css("display", "none");
                } else {
                    if (data.success == true) {
                        if (data.continue == true) {
                            alert("Khai báo sim thành công!");
                            var url = "<?=Yii::app()->createUrl('aCompleteOrders/registerInfo', array('t' => 1))?>";
                            url += '&order_id=' + order_id + '&serial_number=' + serial_number;
                            window.location.href = url;
                        } else {
                            $.ajax({
                                type: "POST",
                                url: '<?=Yii::app()->createUrl('aCompleteOrders/showRoaming')?>',
                                crossDomain: true,
                                data: {
                                    serial_number: serial_number,
                                    phone_customer: <?=$phone_customer?>,
                                    order_id: <?=$order_id?>,
                                    'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
                                },
                                success: function (data) {
                                    $('.modal-backdrop').remove();
                                    var modal_roaming_id = 'modal_roaming_' + order_id;
                                    $('.popup_data_serial').hide();
                                    $('.popup_data_roaming').html(data);
                                    $('#' + modal_roaming_id).modal("show");
                                }
                            });

                        }
                    } else {

                    }
                }
                return true;
            },
            error: function (data) {
                if(data.status == 200){
                    $('.modal-backdrop').remove();
                    var modal_exist_info_id = 'modal_check_exist_info_' + order_id;
                    $('.popup_data_serial').hide();
                    $('.popup_data_exist_info').html(data.responseText);
                    $('#' + modal_exist_info_id).modal("show");
                }else{
                    alert(data);
                }
            }
        });
    }
</script>