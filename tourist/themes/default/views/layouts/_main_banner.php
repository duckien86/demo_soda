<?php if (isset(Yii::app()->user->customer_id) && Yii::app()->user->customer_id != '') {
    $url = Yii::app()->controller->createUrl('site/profile', array('id' => Yii::app()->user->customer_id));
} else {
    $url = $GLOBALS['config_common']['domain_sso']['sso'] . $GLOBALS['config_common']['domain_sso']['pid_aff'];
} ?>

<div class="owl-carousel owl-theme banner">
    <!--<div class="item">
        <img class="banner_main" src="<?= Yii::app()->theme->baseUrl; ?>/images/slider1.jpg">

        <div class="inner_banner">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <a href="<?= $url; ?>" class="btn btn_join">Gia nhập với chúng tôi</a>
            </div>
        </div>
    </div>-->
    <div class="item">
        <a href="<?= Yii::app()->controller->createUrl('package/packageFlexible'); ?>" title="">
            <img class="banner_main" src="<?= Yii::app()->theme->baseUrl; ?>/images/slider3.png">
        </a>
    </div>
<!--    <div class="item">-->
<!--        <a href="https://freedoo.vnpt.vn/25-hoan-tien-100-000d-cho-chu-the-vietinbank-khi-mua-hang-tren-freedoo"-->
<!--           title="">-->
<!--        </a>-->
<!--    </div>-->
    <div class="item">
        <img class="banner_main" src="<?= Yii::app()->theme->baseUrl; ?>/images/slider6.png">
    </div>
    <div class="item">
        <img class="banner_main" src="<?= Yii::app()->theme->baseUrl; ?>/images/slider7.png">
    </div>
</div>

<script type="text/javascript">
    $(window).on('load', function () {
        $('.banner').owlCarousel({
            autoplay: true,
            autoplayTimeout: 5000,
            loop: true,
            items: 1,
            singleItem: true,
            stopOnHover: true
        });
    });
</script>
