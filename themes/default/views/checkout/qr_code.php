<?php
    /* @var $this CheckoutController */
    /* @var $modelOrder WOrders */
    /* @var $modelSim WSim */
    /* @var $modelPackage WPackage */
    /* @var $amount */
    /* @var $qr_code */

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
                    <!-- end #main_right_section -->
                </div>
                <div class="col-md-8 col-md-pull-4 no_pad_xs">
                    <div id="main_left_section">
                        <div class="space_30"></div>
                        <div class="text-center">
                            <?php $this->renderPartial('_qr_code', array(
                                'qr_code'    => $qr_code,
                                'amount'     => $amount,
                                'modelOrder' => $modelOrder,
                            )); ?>
                            <div class="space_30"></div>
                            <?php $this->renderPartial('_list_bank'); ?>
                            <div class="space_30"></div>
                        </div>
                        <div class="space_10"></div>
                        <div class="text-center">
                            <a href="<?= $back_url; ?>"
                               class="btn btn_return" onclick="window.stop();">
                                Quay lại
                            </a>
                        </div>
                    </div>
                    <div class="space_30"></div>
                </div>
                <div class="space_30"></div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function () {
        setTimeout(getState(), 10000);
    });

    function getState() {
        $.ajax({
            type: "GET",
            url: "<?=Yii::app()->controller->createUrl('receiver/getState');?>",
            //            crossDomain: true,
            dataType: 'json',
            timeout: 40000,
            data: {YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"},
            success: function (result) {
                if (result.status == true) {
                    window.location.href = result.url_redirect;
                }
            },
            error: function (x, t, m) {
                if (t === "timeout") {
                    getState();
                } else {
                    console.log(t);
                }
            }
        });
    }

    $("a").click(function (event) {
        window.stop();
    });
</script>