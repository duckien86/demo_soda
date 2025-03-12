<?php
    /* @var $package WPackage */
?>
<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'modal_alert')
); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 class="text-center"><?php echo Yii::t('web/portal', 'notify'); ?></h4>
</div>
<div class="modal-body">
    <div id="body-content"></div>
    <div class="space_30"></div>
    <div class="pull-right">
        <?= CHtml::link(Yii::t('web/portal', 'close'), '#', array('class' => 'btn btn_green', 'data-dismiss' => 'modal')) ?>
    </div>
    <div class="space_1"></div>
</div>
<?php $this->endWidget(); ?>
