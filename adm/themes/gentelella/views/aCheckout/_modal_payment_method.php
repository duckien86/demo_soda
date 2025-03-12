<?php
    /* @var $qr_code */
    /* @var $amount */
?>

<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'modal_pm')
); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <img src="<?= Yii::app()->theme->baseUrl; ?>/images/logo_freedoo.png" class="logo">
</div>
<div class="modal-body">
    <div class="space_10"></div>
    <div class="msg"></div>
    <div class="space_10"></div>
    <div class="col-md-12">
        <?php $this->renderPartial('/checkout/qr_code', array(
            'qr_code_data' => $qr_code,
            'amount'       => $amount,
        )); ?>
    </div>
</div>
<div class="space_30"></div>
<?php $this->endWidget(); ?>
