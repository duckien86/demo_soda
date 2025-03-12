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
                        <div>
                            <p class="text-center">
                                <span class="lbl_color_pink">Yêu cầu của Quý Khách chưa được hoàn thành do lỗi hệ thống.<br>
                                    Mong Quý khách thông cảm và vui lòng thực hiện lại <a href="http://chonso.vinaphone.com.vn/p/chonso/chon-so-ca-nhan.html">TẠI ĐÂY</a></span>
                            </p>

                            <div class="space_10"></div>
                            <div class="lbl_color_pink">
                                <?= (isset($msg)) ? $msg : ''; ?>
                            </div>
                            <div class="space_10"></div>
                            <p>Cảm ơn quý khách hàng đã sử dụng dịch vụ tại <a href="https://freedoo.vnpt.vn" title="">
                                    <span class="lbl_color_blue">https://freedoo.vnpt.vn.</span></a>
                            </p>

                            <p>Mọi thắc mắc xin LH <a href="tel:18001166"><span class="lbl_color_blue">18001166</span></a>(miễn phí).</p>
                        </div>
                        <div class="space_30"></div>
                        <div class="text-center">
                            <?= CHtml::link('Thử lại', Yii::app()->controller->createUrl('sim/index'), array('class' => 'btn btn_continue')) ?>
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