<?php
if (isset(Yii::app()->user->customer_id) && Yii::app()->user->customer_id != '') {
    $url = Yii::app()->controller->createUrl('site/profile', array('id' => Yii::app()->user->customer_id));
} else {
    $url = $GLOBALS['config_common']['domain_sso']['sso'] . $GLOBALS['config_common']['domain_sso']['pid_aff'];
}

$list_banner = WBanners::getListBannerByType(WBanners::TYPE_SLIDER);
?>
<div class="space_60"></div>
<div class="owl-carousel owl-theme banner slider-mobile">
    <?php
    foreach ($list_banner as $banner){
        if(!empty($banner->img_mobile)){ ?>
            <div class="item">
                <a href="<?php echo $banner->target_link?>"
                   title="<?php echo $banner->title?>">
                    <img class="banner_main" src="<?php echo Yii::app()->baseUrl. "/uploads/". $banner->img_mobile; ?>" alt="<?php echo $banner->title?>">
                </a>
            </div>
            <?php
        }
    }
    ?>
</div>
<script>
    $(window).on('load', function () {
        $('.banner').owlCarousel({
            items: 1,
            autoplay: true,
            autoplayTimeout: 5000,
            loop: true,
            singleItem: true,
            navigation: true,
            navigationText: ['<i class="glyphicon glyphicon-chevron-left"></i>', '<i class="glyphicon glyphicon-chevron-right"></i>'],
            pagination: false,
            stopOnHover: true
        });
    });
</script>
