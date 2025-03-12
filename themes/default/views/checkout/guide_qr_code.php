<?php
    /* @var $this CheckoutController */
    /* @var $modelOrder WOrders */
    /* @var $modelSim WSim */
    /* @var $modelPackage WPackage */
    /* @var $amount */
    /* @var $qr_code */
?>
<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <section class="ss-bg">
        <section id="ss-box1" class="ss-box1">
            <div class="container no_pad_xs">
                <div class="ss-box1-right-all">
                    <div class="text-center uppercase font_20 font_bold">
                        Hướng dẫn thanh toán qua ứng dụng mobile banking
                    </div>
                    <div class="space_30"></div>
                    <div class="text-center">
                        <img src="<?= Yii::app()->theme->baseUrl; ?>/document/guide_qr_code_1.png" alt="image">
                        <div class="space_30"></div>
                        <img src="<?= Yii::app()->theme->baseUrl; ?>/document/guide_qr_code_2.png" alt="image">
                    </div>
                </div>
                <div class="space_10"></div>
            </div>
        </section>
        <!-- #ss-box1 -->
    </section>
</div>