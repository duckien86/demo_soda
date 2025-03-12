<?php
    /* @var $this CardController */
    /* @var $form CActiveForm */
    /* @var $operation */
    /* @var $amount */
    /* @var $qr_code */
    /* @var $arr_payment */

    if (isset($operation) && $operation == OrdersData::OPERATION_BUYCARD) {
        $back_url = Yii::app()->controller->createUrl('card/buycard');
    } else {
        $back_url = Yii::app()->controller->createUrl('card/topup');
    }
?>
<div class="form sim_checkout">
    <?php if (Yii::app()->user->hasFlash('danger')): ?>
        <div class="flash-danger help-block font_15">
            <?php echo Yii::app()->user->getFlash('danger'); ?>
        </div>
        <div class="space_30"></div>
    <?php endif; ?>
    <div class="title text-center">Chọn phương thức thanh toán</div>
    <div class="space_10"></div>
    <div class="panel-checkout step-payment">

        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'form_step2',
            'enableAjaxValidation' => TRUE,
        )); ?>
        <div class="main_left_section-actions">
            <?php $this->renderPartial('/checkout/_block_payment_step2', array(
                'arr_payment' => $arr_payment,
                'amount'      => $amount,
                'operation'   => $operation,
            )); ?>
            <div class="space_30"></div>
            <div class="text-center">
                <a href="<?= $back_url; ?>" class="btn btn_return">
                    Quay lại
                </a>
                <?php echo CHtml::submitButton(Yii::t('web/portal', 'payment'), array(
                    'class' => 'btn btn_continue',
                )); ?>
            </div>
            <div class="clear"></div>
        </div>
        <?php $this->endWidget(); ?>
        <div class="space_30"></div>
    </div>
</div>