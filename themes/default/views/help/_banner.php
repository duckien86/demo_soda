<div class="owl-carousel owl-theme banner-help" style="margin-top: 2px;">
    <div class="item">
        <img class="banner_main" src="<?= Yii::app()->theme->baseUrl; ?>/images/help_banner.png">

        <div class="inner_banner_help">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <input type="text" name="search" placeholder="Thắc mắc của bạn" class="search-help">
                <img src="<?= Yii::app()->theme->baseUrl; ?>/images/box-search-hep.png" class="img-search" alt="search">
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
</div>
<script>
    $(window).on('load', function() {
        $('.banner-help').owlCarousel({
            autoplay: true,
            autoplayTimeout: 5000,
            loop: true,
            items: 1,
            singleItem: true,
            stopOnHover: true
        });
    });
</script>
