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
    <!-- font-awesome -->
    <link href="<?= Yii::app()->theme->baseUrl ?>/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/home.css"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/help.css"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/style.css"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/worldcup.css"/>

</head>
<body>
<div id="wrapper" class="no-pad">
    <div id="worldcup_landingpage">



        <?php echo $this->renderPartial('/worldcup/top_menu')?>

        <div id="main-content" class="">
            <div id="worldcup">

                <div class="side-banner side-banner-left">
                    <a target="_blank" href="https://freedoo.vnpt.vn/chi-tiet-goi/fhappy.html">
                        <img src="<?php echo Yii::app()->theme->baseUrl?>/images/banner_worldcup_left.jpg">
                    </a>
                </div>

                <div class="container_wc">
                    <?php echo $content ?>
                </div>

                <div class="side-banner side-banner-right">
                    <a target="_blank" href="https://freedoo.vnpt.vn/sim-so.html">
                        <img src="<?php echo Yii::app()->theme->baseUrl?>/images/banner_worldcup_right.jpg">
                    </a>
                </div>
            </div>
        </div>

        <?php echo $this->renderPartial('/worldcup/footer')?>

    </div>
</div>
<!-- End wrapper -->
</body>
</html>
