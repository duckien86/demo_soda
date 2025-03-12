<?php
if (isset(Yii::app()->user->customer_id) && Yii::app()->user->customer_id != '') {
    $url = Yii::app()->controller->createUrl('site/profile', array('id' => Yii::app()->user->customer_id));
} else {
    $url = $GLOBALS['config_common']['domain_sso']['sso'] . $GLOBALS['config_common']['domain_sso']['pid_aff'];
}

$list_banner = WBanners::getListBannerByType(WBanners::TYPE_MYTV_SIDE);
?>

<div class="owl-carousel owl-theme banner" style="width: 100%;float: left">

    <?php
    foreach ($list_banner as $banner){
        if(!empty($banner->img_desktop)){ ?>
            <div class="item">
                <a href="<?php echo $banner->target_link?>"
                   title="<?php echo $banner->title?>">
                    <img class="banner_main" src="<?php echo Yii::app()->baseUrl. "/uploads/". $banner->img_desktop; ?>" alt="<?php echo $banner->title?>">
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
            autoplay: true,
            autoplayTimeout: 5000,
            loop: true,
            items: 1,
            singleItem: true,
            stopOnHover: true
        });
    });
</script>