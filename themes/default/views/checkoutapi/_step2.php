<?php
    /* @var $this CheckoutController */
    /* @var $modelOrder WOrders */
    /* @var $payment_method WPaymentMethod */
    /* @var $form CActiveForm */
    /* @var $arr_payment */
    /* @var $amount */
    /* @var $operation */

    $lbl_price_ship = 'miễn phí';
    if ($modelOrder->delivery_type == WOrders::DELIVERY_TYPE_HOME) {
        $amount         -= $GLOBALS['config_common']['order']['price_ship'];
        $lbl_price_ship = number_format($GLOBALS['config_common']['order']['price_ship'], 0, "", ".") . 'đ';
    }
?>
<div class="form sim_checkout">
    <?php if(!empty($modelOrder->promo_code)):?>
    <div class="font_15">
        Bạn đã nhập mã giới thiệu/mã khuyến mại là: <?= $modelOrder->promo_code;?>
    </div>
    <div class="space_10"></div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('danger')): ?>
        <div class="flash-danger help-block font_15">
            <?php echo Yii::app()->user->getFlash('danger'); ?>
        </div>
        <div class="space_1"></div>
    <?php endif; ?>
    <div class="title text-center">Chọn phương thức thanh toán</div>
    <div class="space_10"></div>
    <div class="panel-checkout step-payment">
        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'form_step2',
            'action'               => Yii::app()->controller->createUrl('checkoutapi/checkout2'),
            'enableAjaxValidation' => TRUE,
        )); ?>
        <div class="main_left_section-actions">
            <?php $this->renderPartial('_block_payment_step2', array(
                'arr_payment'    => $arr_payment,
                'amount'         => $amount,
                'operation'      => $operation,
                'lbl_price_ship' => $lbl_price_ship,
            )); ?>
            <div class="space_30"></div>
            <div class="text-center">
                <a href="<?= Yii::app()->controller->createUrl('checkoutapi/checkout'); ?>" class="btn btn_return">
                    Quay lại
                </a>
                <?php echo CHtml::submitButton(Yii::t('web/portal', 'payment'), array(
                    'class' => 'btn btn_continue',
                )); ?>
            </div>
            <div class="clear"></div>
        </div>
        <?php $this->endWidget(); ?>
        <div class="space_10"></div>
    </div>
</div>
