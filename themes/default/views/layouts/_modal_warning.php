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
        <?php if(WAffiliateManager::checkApiCheckout(Yii::app()->request->cookies['utm_source'])){ ?>
            <?= CHtml::link(Yii::t('web/portal', 'continue'), Yii::app()->controller->createUrl('checkoutapi/checkout'), array('class' => 'btn btn_green')) ?>
        <?php }else{ ?>
            <?= CHtml::link(Yii::t('web/portal', 'continue'), Yii::app()->controller->createUrl('checkout/checkout'), array('class' => 'btn btn_green')) ?>
        <?php } ?>
    </div>
    <div class="space_1"></div>
</div>
<?php $this->endWidget(); ?>
<script>
    $(document).on('click', '#btn_remove_keep', function (e) {
        $(this).bind('click', false);
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('sim/removeKeepMsisdn');?>",
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
                    $('#msg_remove_keep').html('Hủy đơn hàng thất bại. Vui lòng thử lại.');
                }
            }
        });
    });

    $('#modal_warning').on('hidden.bs.modal', function () {
        $(this).css('display', 'none');
    })
</script>
