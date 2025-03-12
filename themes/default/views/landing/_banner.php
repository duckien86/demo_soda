<div class="owl-carousel owl-theme banner">
    <div class="item">
        <img class="banner_main" src="<?= Yii::app()->theme->baseUrl; ?>/images/landing_banner.png">

        <div class="inner_banner">
            <div class="col-md-6"></div>
        </div>
    </div>
</div>

<script>
    $(window).on('load', function() {
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