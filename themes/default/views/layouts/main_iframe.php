<!DOCTYPE html>
<html>
<head>
    <meta id="viewport" name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="UTF-8"/>
    <meta property="og:url" content="<?= 'http://' . SERVER_HTTP_HOST . CHtml::encode($_SERVER['REQUEST_URI']); ?>"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="Freedoo"/>
    <meta property="og:description" content="<?php echo CHtml::encode($this->pageDescription); ?>"/>
    <meta property="og:image" content="<?php echo CHtml::encode($this->pageImage); ?>"/>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="shortcut icon" href="<?= Yii::app()->theme->baseUrl ?>/images/icon-fav.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="<?= Yii::app()->theme->baseUrl ?>/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="<?= Yii::app()->theme->baseUrl ?>/css/owl.carousel.min.css">
    <!-- font-awesome -->
    <link href="<?= Yii::app()->theme->baseUrl ?>/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/sim.css"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/home.css"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/step.css"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/style.css"/>

    <script src="<?= Yii::app()->theme->baseUrl ?>/js/owl.carousel.min.js"></script>
    <script src="<?= Yii::app()->theme->baseUrl ?>/js/wow.min.js"></script>
    <script src="<?= Yii::app()->theme->baseUrl ?>/js/custom.js"></script>
    <script src="<?= Yii::app()->theme->baseUrl ?>/js/main.js"></script>
    <script>
        var wow = new WOW(
            {
                animateClass: 'animated', // animation css class (default is animated)
                offset: 0,          // distance to the element when triggering the animation (default is 0)
                callback: function (box) {
                }
            }
        );
        wow.init();
    </script>
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '184750646120002');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=184750646120002&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->
</head>
<body>
<div id="wrapper" class="no-pad">
    <div id="main-content" class="">
        <?php echo $content ?>
    </div>
    <div id="bottom_content"></div>
</div>
<!-- End wrapper -->

<?php $this->renderPartial('//layouts/_modal_alert'); ?>
<?php $this->renderPartial('//layouts/_modal_warning'); ?>
</body>
</html>
