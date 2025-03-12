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
                            <?php $form = $this->beginWidget('CActiveForm', array(
                                'id'                   => 'create_sim_form',
                                'enableAjaxValidation' => TRUE,
                            )); ?>
                            <h3 class="title text-center">
                                Vui lòng nhập 10 số serial để khai báo sim
                            </h3>

                            <div class="space_30"></div>
                            <div class="text-center form-group help-block error">
                                <?= (isset($msg)) ? $msg : ''; ?>
                            </div>
                            <div class="form-group text-center">
                                <?php echo $form->textField($model, 'serial_number', array('class' => 'textbox', 'size' => 30, 'maxlength' => 255)); ?>
                                <?php echo $form->error($model, 'serial_number'); ?>
                            </div>

                            <div class="space_60"></div>
                            <div class="text-center">
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
<script type="text/javascript">
    <?php if ($success == TRUE):?>
    var order_id = <?= $order_id?>;
    var serial_number = <?= $serial_number?>;
    alert("<?php echo $msg?>");
    var url = "<?=Yii::app()->createUrl('aCompleteOrders/registerInfo', array('t' => 1))?>";
    url += '&order_id=' + order_id + '&serial_number=' + serial_number;
    window.location.href = url;
    <?php endif;?>



    if (window.history && window.history.pushState) {
        window.history.pushState('forward', null, '');

        $(window).on('popstate', function () {
            window.location.reload();
        });
    }

</script>
