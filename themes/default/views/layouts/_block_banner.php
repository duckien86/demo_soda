<div id="block_banner" class="owl-carousel owl-theme">
    <div class="item">
        <img src="<?= Yii::app()->theme->baseUrl; ?>/images/slider2.png" alt="image">
    </div>
    <div class="item">
        <img src="<?= Yii::app()->theme->baseUrl; ?>/images/slider2.png" alt="image">
    </div>
</div>

<script>
    $(window).on('load', function() {
        $('#block_banner').owlCarousel({
            autoplay: true,
            autoplayTimeout: 2000,
            loop: true,
            items: 1,
            singleItem: true,
            stopOnHover: true
        });
    });
</script>
