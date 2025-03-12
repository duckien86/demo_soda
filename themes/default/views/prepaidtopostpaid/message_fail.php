<div class="page_detail">
    <section class="ss-bg">
        <div class="container">
            <div class="checkout-process">
                <div class="col-md-4 col-md-push-8 no_pad_xs">
                    <div id="main_right_section">
                        <?php echo Yii::app()->session['html_pack_order']; ?>
                    </div>
                </div>
                <div class="col-md-8 col-md-pull-4 no_pad_xs">
                    <div id="main_left_section" class="msg">
                        <div class="text-center">
                            <div class="space_30"></div>
                            <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_logo_fd.png" alt="image">
                            </div>
                        </div>
                        <div class="space_30"></div>
                        <div>
                            <p class="text-center">
                                <span class="lbl_color_pink">Đăng ký không thành công</span>
                            </p>

                            <div class="lbl_color_pink">
                                <?= (isset($msg)) ? $msg : ''; ?>
                            </div>
                            <div class="space_30"></div>
                            <p>Bạn vui lòng liên hệ tổng đài <a href="tel:18001166"><span class="lbl_color_blue">18001166</span></a> (miễn phí) để biết thêm chi tiết.</p>

                            <p>Trân trọng cảm ơn!</p>
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