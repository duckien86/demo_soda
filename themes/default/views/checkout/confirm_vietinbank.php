<?php
    /* @var $this CheckoutController */
    /* @var $modelOrder WOrders */
    /* @var $modelSim WSim */
    /* @var $modelPackage WPackage */
    /* @var $otpModel OtpForm */
    /* @var $form CActiveForm */
    /* @var $amount */
    /* @var $operation */
    /* @var $vietinbank Vietinbank */

    if (isset($operation) && $operation == OrdersData::OPERATION_BUYSIM) {
        $back_url    = Yii::app()->controller->createUrl('checkout/checkout2');
        $panel_order = Yii::app()->session['html_order'];
    } else {
        $back_url    = Yii::app()->controller->createUrl('card/checkout2');
        $panel_order = Yii::app()->session['html_card_order'];
    }
?>
<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <section class="ss-bg">
        <div class="container no_pad_xs">
            <div class="checkout-process">
                <div class="col-md-4 col-md-push-8 no_pad_xs">
                    <div id="main_right_section">
                        <?php echo $panel_order; ?>
                    </div>
                </div>
                <div class="col-md-8 col-md-pull-4 no_pad_xs">
                    <div id="main_left_section">

                        <div class="space_30"></div>
                        <div class="form">
                            <?php $form = $this->beginWidget('CActiveForm', array(
                                'id'     => 'payment_confirmation',
                                'method' => 'post',
                                'action' => $vietinbank->end_point,
                            )); ?>
                            <?php
                                $params = $vietinbank->req_ary_param;
                                foreach ($params as $name => $value) {
                                    echo CHtml::hiddenField($name, $value);
                                }
                            ?>

                            <div class="title font_16 text-center">
                                Quý khách vui lòng xác nhận chọn cổng thanh toán Vietinbank
                            </div>
                            <div class="space_60"></div>
                            <div class="text-center">
                                <a href="<?= $back_url; ?>"
                                   class="btn btn_return">
                                    Quay lại
                                </a>
                                <?php echo CHtml::submitButton(Yii::t('web/portal', 'verify'), array(
                                    'class' => 'btn btn_continue'
                                )); ?>
                            </div>
                            <?php $this->endWidget(); ?>
                        </div>
                        <div class="space_30"></div>
                    </div>

                    <div class="space_10"></div>
                </div>
                <!-- end #main_right_section -->
                <div class="space_30"></div>
            </div>
        </div>
    </section>
</div>