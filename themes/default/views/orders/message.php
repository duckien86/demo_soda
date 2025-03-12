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
                <div class="col-md-12">
                    <div id="main_left_section" class="msg">
                        <div class="text-center">
                            <div class="lbl_color_pink">
                                <?= (isset($msg)) ? $msg : ''; ?>
                            </div>
                            <div class="space_30"></div>
                            <p>
                                <span class="lbl_color_blue">
                                    <?php
                                        if (Yii::app()->request->hostInfo == 'http://222.252.19.197:8694') {
                                            $url = 'http://222.252.19.197:8694/sso/login/' . $GLOBALS['config_common']['domain_sso']['pid'];
                                        } else {
                                            $url = $GLOBALS['config_common']['domain_sso']['sso'] . $GLOBALS['config_common']['domain_sso']['pid'];
                                        }
                                    ?>
                                    <?= CHtml::link('Đăng nhập', $url, array()) ?>
                                </span>
                            </p>

                            <p>Mọi thắc mắc xin LH <a href="tel:18001166"><span
                                            class="lbl_color_blue">18001166</span></a>(miễn phí).</p>
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