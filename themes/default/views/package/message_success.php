<?php
    /* @var $this PackageController */
    /* @var $order_id */

    $html_content = '';
    $package      = array();
    if (isset(Yii::app()->request->cookies['html_package_cache_key'])
        && !empty(Yii::app()->request->cookies['html_package_cache_key']->value)
    ) {
        $cache_key = Yii::app()->request->cookies['html_package_cache_key']->value;
        $arr_cache = Yii::app()->cache->get($cache_key);
        if ($arr_cache) {
            $html_content = $arr_cache['html_content'];
            $package      = $arr_cache['modelPackage'];
        }
    }
?>
<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <section class="ss-bg">
        <div class="container">
            <div class="checkout-process">
                <div class="col-md-4 col-md-push-8 no_pad_xs">
                    <div id="main_right_section">
                        <?php echo $html_content; ?>
                    </div>
                </div>
                <div class="col-md-8 col-md-pull-4 no_pad_xs">
                    <div id="main_left_section" class="msg">
                        <div class="text-center">
                            <div class="space_30"></div>
                            <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_success.png" alt="image">
                            </div>
                        </div>
                        <div class="space_30"></div>
                        <div>
                            <?php
                                $package_name = '';
                                $price        = 0;
                                if ($package) {
                                    $price = $package['price'];
                                    if ($package['name'] == WPackage::PACKAGE_FLEXIBLE) {
                                        $package_name = Yii::t('web/portal', strtolower($package['name']));
                                    } else {
                                        $package_name = CHtml::encode($package['name']);
                                    }
                                }
                            ?>
                            <p>
                                <span
                                        class="lbl_color_pink">Chúc mừng bạn đã đăng ký thành công gói cước <?= $package_name ?>
                                    giá gói <?= number_format(($price), 0, "", ".") ?>đ</span>
                            </p>

                            <p>Chi tiết liên hệ <a href="tel:18001166"><span class="lbl_color_blue">18001166</span></a>
                                (miễn phí)</p>

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
<div id="survey">
    <?php $this->renderPartial('/survey/_modal_confirm', array('order_id' => $order_id)); ?>
</div>
