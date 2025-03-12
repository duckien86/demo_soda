<?php
    /* @var $this CheckoutController */
    /* @var $order_id */
    $is_couple = CFunction_MPS::isPhoneCouple(Yii::app()->session['message_order']['msisdn']);
?>
<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <section class="ss-bg">
        <div class="container">
            <div class="checkout-process">
                <div class="col-md-4 col-md-push-8 no_pad_xs">
                    <div id="main_right_section">
                        <?php echo Yii::app()->session['html_order']; ?>
                    </div>
                </div>
                <div class="col-md-8 col-md-pull-4 no_pad_xs">
                    <?php $this->renderPartial('_back_chonso'); ?>
                    <div id="main_left_section" class="msg">
                        <div class="text-center">
                            <div class="space_30"></div>
                            <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_success.png" alt="image">
                            </div>
                        </div>
                        <div>
                            <div class="space_30"></div>
                            <p>Bạn đã đặt mua thành công số <span
                                        class="lbl_color_pink"><?= Yii::app()->session['message_order']['msisdn']; ?></span>
                                giá <span
                                        class="lbl_bold"><?= number_format(Yii::app()->session['message_order']['price'], 0, "", ".") ?>
                                    đ</span><br><br> Mã đơn hàng <span
                                        class="lbl_bold"><?= Yii::app()->session['message_order']['order_id']; ?></span>.
                            </p>

                            <?php
                                //delivery_type=shop
                                if (isset(Yii::app()->session['message_order']['delivery_type']) && Yii::app()->session['message_order']['delivery_type'] == WOrders::DELIVERY_TYPE_SHOP):
                                    $time = time();
                                    if (isset(Yii::app()->session['message_order']['create_date']) && !empty(Yii::app()->session['message_order']['create_date'])) {
                                        $time = Yii::app()->session['message_order']['create_date'];
                                    }
                                    $time = date('d-m-Y H:i:s', $time + ((48 * 60) * 60));
                                    ?>
                                    <div class="space_10"></div>
                                    <?php if($is_couple){ ?>
                                        <p>  Bạn vui lòng mang theo mã đơn hàng, MXT và CMND ra điểm giao dịch đã chọn để làm thủ tục hòa mạng trong vòng 48h.</p>
                                    <?php }else{ ?>
                                        <p> Đơn hàng có hiệu lực đến <?= $time; ?>. Vui lòng mang theo Mã đơn hàng và
                                        CMND ra điểm giao dịch đã chọn làm thủ tục hòa mạng.</p>
                                    <?php } ?>

                                    <p>Cảm ơn bạn đã sử dụng dịch vụ <a title="">
                                            <span class="lbl_color_blue">VNPT</span></a>
                                        
                                <?php else: ?>
                                    <div class="space_10"></div>
                                    <?php if($is_couple){ ?>
                                        <p>Chúng tôi sẽ liên hệ theo số điện thoại <span
                                                    class="lbl_bold"><?= Yii::app()->session['message_order']['phone_contact']; ?></span>
                                            để xác nhận giao hàng trong vòng 48h.</p>
                                    <?php }else{ ?>
                                        <p>Chúng tôi sẽ liên hệ theo số điện thoại <span
                                                class="lbl_bold"><?= Yii::app()->session['message_order']['phone_contact']; ?></span>
                                            để xác nhận giao hàng trong vòng 48h.</p>
                                    <?php } ?>
                                    <?php
                                        if (isset(Yii::app()->session['message_order']['delivery_type']) && Yii::app()->session['message_order']['delivery_type'] == WOrders::DELIVERY_TYPE_HOME):?>
                                        <p class="lbl_color_blue lbl_bold">* Lưu ý: Vui lòng thanh toán riêng phí
                                            giao
                                            hàng cho nhân viên giao vận.</p>
                                    <?php endif; ?>
                                    <p>Cảm ơn bạn đã sử dụng dịch vụ <a  title="">
                                            <span class="lbl_color_blue">VNPT</span></a>
                                <?php endif; ?>
                        </div>
                        <div class="space_10"></div>
                        <div class="text-center">
                            <?php echo CHtml::link(Yii::t('web/portal', 'homepage'), Yii::app()->controller->createUrl('site/index'),
                                array('class' => 'btn btn_gray')); ?>

                            <?php
                                //                                echo CHtml::link(Yii::t('web/portal', 'search_order'), Yii::app()->controller->createUrl('orders/index'),
                                //                                array('class' => 'btn btn_gray'));
                            ?>
                        </div>
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