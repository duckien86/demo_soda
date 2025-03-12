<link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl . '/css/mobile-new.css' . $version; ?>" />
<!--list-product-mobile-home-->
<div class="list-product-mobile-home">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-pad-mar">
                <?php $list_package = WBanners::getListBannerByType(WBanners::TYPE_PACKAGE); ?>
                <!--item-product-mobile-home-->
                <?php foreach ($list_package as $item) { ?>
                    <?php if (isset($item->img_mobile) && $item->img_mobile != '') { ?>
                        <div class="item-product-mobile-home">
                            <a href="<?php echo $item->target_link ?>"><img src="<?php echo Yii::app()->baseUrl . "/uploads/" . $item->img_mobile; ?>" alt="<?php echo $item->title ?>"></a>
                        </div>
                    <?php } ?>
                <?php } ?>
                <!--end item-product-mobile-home-->
            </div>
        </div>
    </div>
</div>
<!--end list-product-mobile-->
<!--make-money-send-mail-->
<div class="make-money-send-mail">
    <div class="container">
        <div class="row">
            <!--make-money-->
            <div class="make-money" style="margin-bottom: 30px">
                <div class="wrap-make-money">
                    <a href="<?= $GLOBALS['config_common']['domain_related']['affiliate'] ?>" style="color: #fff"> kiếm
                        tiền online cùng GShop</a>
                </div>
            </div>
            <!--end make-money-->
        </div>

    </div>
</div>
<!--end make-money-send-mail-->
<!--option-collapsible-->
<div class="option-collapsible" data-toggle="collapse" data-target="#search_order">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-pad-mar">
                <!--item-option-collapsible-->
                <div class="item-option-collapsible">
                    <span class="s-left">Tra cứu đơn hàng</span> <span class="s-right"><img src="<?= Yii::app()->theme->baseUrl; ?>/mobile_images/down-new.png" alt=""></span>
                </div>
                <!--end item-option-collapsible-->

            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-pad-mar">
                <div id="search_order" class="collapse">
                    <ul class="ul-option">
                        <li><a href="<?= Yii::app()->controller->createUrl('orders/searchOrder'); ?>">Tra cứu</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end option-collapsible-->
<!--option-collapsible-->
<div class="option-collapsible" data-toggle="collapse" data-target="#contact-new">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-pad-mar">
                <!--item-option-collapsible-->
                <!-- <div class="item-option-collapsible">
                    <span class="s-left"><a href="javascript:$zopim.livechat.window.show();" style="color: #333;">Liên hệ</a></span>
                </div>-->
                <!--end item-option-collapsible-->

            </div>
        </div>
    </div>
</div>
<!--end option-collapsible-->
<!--option-collapsible-->
<div class="option-collapsible" data-toggle="collapse" data-target="#support-new">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-pad-mar">
                <!--item-option-collapsible-->
                <div class="item-option-collapsible">
                    <span class="s-left">Hỗ trợ</span> <span class="s-right"><img src="<?= Yii::app()->theme->baseUrl; ?>/mobile_images/down-new.png" alt=""></span>
                </div>
                <!--end item-option-collapsible-->

            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-pad-mar">
                <div id="support-new" class="collapse">
                    <ul class="ul-option">
                        <li><a href="<?= Yii::app()->createUrl('help/supportSell') ?>">Hướng dẫn mua sim số</a></li>
                        <li><a href="<?= Yii::app()->createUrl('help/supportProduct') ?>">Hướng dẫn mua gói cước - nạp
                                thẻ</a></li>
                        <li><a href="<?= $GLOBALS['config_common']['domain_related']['social'] ?>">Hỏi cộng đồng</a>
                        </li>
                        <!-- <li><a href="javascript:$zopim.livechat.window.show();">Hỏi tư vấn viên</a></li>-->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end option-collapsible-->
<!--share-new-->
<div class="share-new">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-pad-mar">
                <div class="wrap-share text-right">
                    <span><a href="<?= Yii::app()->controller->createUrl('site/index'); ?>"><img src="<?= Yii::app()->theme->baseUrl; ?>/mobile_images/icon_fb.png" alt="icon_fb"></a></span>
                    <span><a href="<?= Yii::app()->controller->createUrl('site/index'); ?>"><img src="<?= Yii::app()->theme->baseUrl; ?>/mobile_images/icon_gg.png" alt="icon_gg"></a></span>
                    <span><a href="<?= Yii::app()->controller->createUrl('site/index'); ?>"><img src="<?= Yii::app()->theme->baseUrl; ?>/mobile_images/icon_tw.png" alt="icon_tw"></a></span>
                    <span><a href="<?= Yii::app()->controller->createUrl('site/index'); ?>"><img src="<?= Yii::app()->theme->baseUrl; ?>/mobile_images/icon_in.png" alt="icon_in"></a></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end share-new-->
<!--coppyright-new-->
<div class="coppyright-new">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-pad-mar">
                <div class="cpyr">
                    <span><a href="<?= Yii::app()->controller->createUrl('site/index'); ?>"> <img class="trademark" src="<?= Yii::app()->theme->baseUrl; ?>/mobile_images/trademark.png" alt="trademark"></a></span>
                    <span><a href="<?= Yii::app()->controller->createUrl('site/index'); ?>"> <img class="vina" src="<?= Yii::app()->theme->baseUrl; ?>/mobile_images/vina.png" alt="vina"></a></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end coppyright-new-->