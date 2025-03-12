<?php
    /* @var $package WPackage */
?>
<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'confirm')
); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 class="text-center"><?php echo Yii::t('web/portal', 'confirm'); ?></h4>
</div>
<div class="modal-body">
    <p class="font_15">Bạn có chắc chắn muốn Hủy dịch vụ?</p>
    <div class="space_30"></div>
    <div class="text-center">
        <?= CHtml::hiddenField('package_code_cancel') ?>
        <?= CHtml::link(Yii::t('web/portal', 'confirm'), 'javascript:void(0);',
            array(
                'id'    => 'btn_cancel_pack',
                'class' => 'btn btn_green',
            )) ?>
        <?= CHtml::link(Yii::t('web/portal', 'cancel'), '#', array('class' => 'btn btn_green', 'data-dismiss' => 'modal')) ?>
    </div>
</div>
<?php $this->endWidget(); ?>

<script>
    $(document).on('click', '#btn_cancel_pack', function (e) {
        $(this).bind('click', false);
        var package_code = $('#package_code_cancel').val();
        $.ajax({
            url: '<?=Yii::app()->controller->createUrl('package/cancelPackage')?>',
            type: 'post',
            cache: false,
            dataType: "json",
            data: {
                package_code: package_code,
                YII_CSRF_TOKEN: '<?=Yii::app()->request->csrfToken;?>'
            },
            success: function (result) {
                $(this).unbind('click', false);
                window.location.href = "<?=Yii::app()->controller->createUrl('orders/index')?>";
            },
            error: function (request, status, err) {
                $(this).unbind('click', false);
            }
        });
    });
</script>
