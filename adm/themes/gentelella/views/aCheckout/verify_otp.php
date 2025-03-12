<?php
    /* @var $this CheckoutController */
    /* @var $modelOrder WOrders */
    /* @var $modelSim WSim */
    /* @var $modelPackage WPackage */
    /* @var $otpModel OtpForm */
    /* @var $otpForm CActiveForm */
    /* @var $amount */

    $back_url    = Yii::app()->controller->createUrl('aCheckout/checkout');
    $panel_order = Yii::app()->session['html_order'];

?>
<div class="page_detail">
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

                        <div class="form">
                            <?php $otpForm = $this->beginWidget('CActiveForm', array(
                                'id'                   => 'otp_form',
                                'enableAjaxValidation' => TRUE,
                            )); ?>
                            <h3 class="title text-center">
                                Quý khách vui lòng nhập mã xác nhận đã được chúng tôi gửi đến số điện thoại liên hệ
                            </h3>

                            <div class="space_30"></div>
                            <div class="text-center form-group help-block error">
                                <?= (isset($msg)) ? $msg : ''; ?>
                            </div>
                            <?php
                                if (YII_DEBUG == TRUE) {
                                    echo Yii::app()->session['token_key'];
                                }
                            ?>
                            <div class="form-group text-center">
                                <?php echo $otpForm->labelEx($otpModel, 'token'); ?>
                                <?php echo $otpForm->textField($otpModel, 'token', array('class' => 'textbox', 'size' => 30, 'maxlength' => 255)); ?>
                                <?php echo $otpForm->error($otpModel, 'token'); ?>
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