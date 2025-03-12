<?php
    /* @var $this CardController */
    /* @var $modelOrder WOrders */
    /* @var $orderDetails WOrderDetails */
?>
<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <section class="ss-bg">
        <div class="container">
            <div class="checkout-process">
                <div class="col-md-4 col-md-push-8 no_pad_xs">
                    <div id="main_right_section">
                        <?php echo Yii::app()->session['html_card_order']; ?>
                    </div>
                </div>
                <div class="col-md-8 col-md-pull-4 no_pad_xs">
                    <div id="main_left_section" class="msg">
                        <div>
                            <?php
                                if (isset($msg))    :
                                    ?>
                                    <div class="text-center">
                                        <span class="lbl_color_pink"><?= $msg ?></span>
                                    </div>
                                <?php else: ?>
                                    <p class="text-center">
                                        <span class="lbl_color_pink"> Giao dịch lỗi. Quý khách vui lòng thử lại hoặc liên hệ tổng đài 18001166 (miễn phí)</span>
                                    </p>
                                <?php endif; ?>
                            <div class="space_30"></div>
                            <p>Cảm ơn quý khách đã sử dụng dịch vụ tại <a href="https://freedoo.vnpt.vn" title="">
                                    <span class="lbl_color_blue">https://freedoo.vnpt.vn.</span></a>
                            </p>

                            <p>Mọi thắc mắc xin LH <a href="tel:18001166"><span class="lbl_color_blue">18001166</span></a> (miễn phí).</p>

                            <div class="space_30"></div>
                            <div class="text-center">
                                <?= CHtml::link('Thử lại', Yii::app()->controller->createUrl('sim/index'), array('class' => 'btn btn_continue')) ?>
                            </div>
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