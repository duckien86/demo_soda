<?php
    /* @var $package WPackage */
?>
<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'confirm_register')
); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 class="text-center"><?php echo Yii::t('web/portal', 'confirm'); ?></h4>
</div>
<div class="modal-body">
    <div id="msg_confirm_register">
    </div>
    <div class="space_30"></div>
    <div class="text-center">
        <?= CHtml::link(Yii::t('web/portal', 'confirm'),
            $this->createUrl('package/registerPriceDiscount'),
            array('class' => 'btn btn_green','id'=>'btn_reg_package')) ?>
        <?= CHtml::link(Yii::t('web/portal', 'cancel'), '#', array('class' => 'btn btn_green', 'data-dismiss' => 'modal')) ?>
    </div>
</div>
<?php $this->endWidget(); ?>
