<?php if ($msg_end && $order_id && $response):
    ?>
    <div class="modal" id="modal_result_package_<?php echo $order_id; ?>" role="dialog" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog" style="width: 35%; text-align: center;">
            <div class="modal-content">
                <div class="loading-div">
                </div>
                <div class="modal-body" style="margin-top: 30px;">
                    <?php if ($response == 1): ?>
                        <?php echo "Hoàn thành giao hàng!"; ?>
                    <?php else: ?>
                        <span style="color: red;"><?php echo $msg_end; ?></span>
                    <?php endif; ?>
                </div>
                <div class="modal-footer" style="text-align: center;">
                    <?php echo CHtml::button('OK',
                        array("onclick" => "check_register_package('$order_id');",
                              "id"      => "success-package",
                              "class"   => "btn btn-success continue_register_package_button",

                        )); ?>
                </div>
            </div>

        </div>
    </div>
<?php endif; ?>
<script type="text/javascript">

    function check_register_package(order_id) {
        <?php if ($response == 1):?>
        window.location.href = "<?= Yii::app()->createUrl("aCompleteOrders/admin")?>";
        <?php else:?>
        $('.close').click(function () {
            $('.modal-backdrop').remove();
        });
        $('.close-button').click(function () {
            $('.modal-backdrop').remove();
        });
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
        <?php endif;?>
    }


</script>