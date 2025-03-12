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
                    <div id="main_left_section" class="msg">
                        <div>
                            <p class="text-center">
                                <?php if(isset($_GET['t']) && $_GET['t'] == 2){ ?>
                                    <span class="lbl_color_pink"><?= (isset($msg)) ? $msg : ''; ?></span>
                                <?php }else{ ?>
                                        <?php if(isset($_GET['t']) && $_GET['t'] == 'STK-1234'){ // do nothing ?>
                                         <?php }else{?>
                                            <span class="lbl_color_pink">Đơn hàng không thành công, vui lòng thử lại</span>
                                         <?php }?>
                                <?php }?>
                            </p>

                            <div class="space_10"></div>
                            <?php if(isset($_GET['t']) && $_GET['t'] == 2){ ?>
                                <div class="lbl_color_pink">
                                    Hãy bỏ qua thông báo này nếu bạn đã nhận được tin nhắn đơn hàng thành công!
                                </div>
                            <?php }else{ ?>
                                <div class="lbl_color_pink">
                                    <?= (isset($msg)) ? $msg : ''; ?>
                                </div>
                            <?php } ?>

                            <div class="space_10"></div>
                            <p>Cảm ơn quý khách hàng đã sử dụng dịch vụ tại <a href="https://freedoo.vnpt.vn" title="">
                                    <span class="lbl_color_blue">https://freedoo.vnpt.vn.</span></a>
                            </p>

                            <p>Mọi thắc mắc xin LH <a href="tel:18001166"><span class="lbl_color_blue">18001166</span></a>(miễn phí).</p>
                        </div>
                        <div class="space_30"></div>
                        <div class="text-center">
                            <?php if(isset($_GET['t']) && $_GET['t'] == 2){ ?>
                                <?= CHtml::link('Về trang chọn số', Yii::app()->controller->createUrl('sim/index'), array('class' => 'btn btn_continue')) ?>
                            <?php }else{ ?>
                                <?php if(isset($_GET['t']) && $_GET['t'] == 'STK-1234'){ ?>
                                    <?php echo CHtml::link(Yii::t('web/portal', 'homepage'), Yii::app()->controller->createUrl('site/index'),
                                        array('class' => 'btn btn_gray')); ?>
                                <?php }else{?>
                                    <?= CHtml::link('Thử lại', Yii::app()->controller->createUrl('sim/index'), array('class' => 'btn btn_continue')) ?>
                                <?php }?>
                            <?php } ?>

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