<?php
    $orders_data     = Yii::app()->session['orders_data']->orders;
    $back_package    = TRUE;
    $back_serial_sim = TRUE;
    if (Yii::app()->cache->get('createSim_' . $orders_data->id)) {
        $back_package = FALSE;
    }
    if (Yii::app()->cache->get('registerSimCell_' . $orders_data->id)) {
        $back_serial_sim = FALSE;
    }

?>
<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'modal_warning', 'htmlOptions' => array('data-backdrop' => 'static', 'data-keyboard' => 'false'))
); ?>
<div class="modal-header">
    <h4 class="text-center"><?php echo Yii::t('web/portal', 'notify'); ?></h4>
</div>
<div class="modal-body">
    <div class="text-center">
        Bạn muốn hủy đơn hàng hiện tại?
    </div>
    <div id="msg_remove_keep">
    </div>
    <div class="space_30"></div>
    <div class="text-center">
        <?= CHtml::link(Yii::t('web/portal', 'confirm'), 'javascript:void(0);', array('id' => 'btn_remove_keep', 'class' => 'btn btn_green')) ?>
        <?php if ($back_serial_sim) : ?>
            <?= CHtml::link(Yii::t('web/portal', 'continue'), ($back_package) ? Yii::app()->controller->createUrl('aCheckout/checkout') : Yii::app()->controller->createUrl('aCheckout/createSerialSim', array('order_id' => $orders_data->id)), array('class' => 'btn btn_green')) ?>
        <?php else: ?>
            <?= CHtml::link(Yii::t('web/portal', 'continue'),Yii::app()->controller->createUrl('aCompleteOrders/registerInfo',array('order_id'=>$orders_data->id, 'serial_number'=>$orders_data->serial_number)), array('class' => 'btn btn_green')) ?>
        <?php endif; ?>
    </div>
    <div class="space_1"></div>
</div>
<?php $this->endWidget(); ?>
<script>
    $(document).on('click', '#btn_remove_keep', function (e) {
        $(this).bind('click', false);
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('aSimAgency/removeKeepMsisdn');?>",
            crossDomain: true,
            dataType: 'json',
            data: {
                curr_controller: "<?=Yii::app()->controller->id?>",
                curr_action: "<?=strtolower(Yii::app()->controller->action->id)?>",
                YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"
            },
            success: function (result) {
                $(this).unbind('click', false);
                if (result.status == true) {
                    $('#modal_warning').modal('hide');
                    if (result.url_redirect != '') {
                        window.location.href = result.url_redirect;
                    }
                } else {
                    $('#msg_remove_keep').html('Hủy đơn hàng thất bại. Bạn phải tiếp tục hoàn tất đơn hàng đã đặt!');
                }
            }
        });
    });

    $('#modal_warning').on('hidden.bs.modal', function () {
        $(this).css('display', 'none');
    })
</script>
