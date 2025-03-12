<?php
    /* @var $this CardController */
    /* @var $modelOrder WOrders */
    /* @var $order_id */
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
                        <div class="text-center">
                            <div class="space_30"></div>
                            <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_success.png" alt="image">
                            </div>
                            <div class="space_30"></div>
                            <?php
                                if (isset(Yii::app()->session['message_card_order']['operation']) && Yii::app()->session['message_card_order']['operation'] == OrdersData::OPERATION_TOPUP): ?>
                                    <p>Số điện thoại <span
                                                class="lbl_color_pink"><?= Yii::app()->session['message_card_order']['phone_contact']; ?></span>
                                        đã được nạp <span
                                                class="lbl_color_pink"><?= number_format(Yii::app()->session['message_card_order']['price'], 0, "", ".") ?>
                                            đ</span> vào tài khoản chính.</p>
                                    <p>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi.</p>
                                <?php else: ?>
                                    <p>Thanh toán thành công, chúng tôi đã gửi mã thẻ mệnh giá <span
                                                class="lbl_color_pink"><?= number_format(Yii::app()->session['message_card_order']['price'], 0, "", ".") ?>
                                            đ</span>
                                        đến email <span
                                                class="lbl_color_pink"><?= Yii::app()->session['message_card_order']['phone_contact']; ?></span>
                                        của quý khách.</p>
                                    <p>Cảm ơn bạn đã sử dụng dịch vụ tại <a href="https://freedoo.vnpt.vn" title="">
                                            <span class="lbl_color_blue">https://freedoo.vnpt.vn</span></a>. Mọi thắc
                                        mắc xin LH <a href="tel:18001166"><span
                                                    class="lbl_color_blue">18001166</span></a> (miễn phí)</p>
                                <?php endif; ?>
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
<div id="survey">
    <?php $this->renderPartial('/survey/_modal_confirm', array('order_id' => $order_id)); ?>
</div>