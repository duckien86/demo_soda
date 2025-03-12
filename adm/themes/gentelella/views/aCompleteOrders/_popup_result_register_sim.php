<?php if ($msg && $order_id):
    ?>
    <div class="modal" id="modal_result_<?php echo $order_id; ?>" role="dialog" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog" style="width: 35%; text-align: center;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-body" style="margin-top: 30px;">
                    <?php if ($response == 1): ?>
                        <?php echo "Bạn dã đăng ký thông tin thuê bao thành công!"; ?>
                    <?php else: ?>
                        <?php echo "Đăng ký thất bại! Vui lòng kiểm tra lại<br/><br/>"; ?>
                        <span style="color: red;"><?php echo $msg; ?></span>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <?php if ($response == 1): ?>
                        <?php echo CHtml::button('Tiếp tục',
                            array("onclick" => "check_exist_package('$order_id');",
                                  "id"      => "$order_id",
                                  "class"   => "btn btn-success continue_register_package_button",

                            )); ?>
                    <?php else: ?>
                        <button type="button" class="btn btn-success continue_register_package_button"
                                data-dismiss="modal">
                            Thử lại
                        </button>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>


<?php endif; ?>
<script type="text/javascript">
    $('.close').click(function () {
        $('.modal-backdrop').remove();
    });
    $('.close-button').click(function () {
        $('.modal-backdrop').remove();
    });
    function check_exist_package(order_id) {
//        $('#modal_result_' + order_id).modal("hide");
        $('.popup_register_result').children().remove("div");
        $.ajax({
            type: "POST",
//            dataType: 'json',
            url: '<?=Yii::app()->createUrl('aCompleteOrders/checkExistPackage')?>',
            crossDomain: true,
            data: {
                order_id: order_id,
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>'
            },
            success: function (data) {
                $('.modal-backdrop').remove();
                $('.popup_register_result').html(data);
                var modal_result_package = 'modal_result_package_' + order_id;

                $('#' + modal_result_package).modal("show");
            }
        });
    }
</script>
