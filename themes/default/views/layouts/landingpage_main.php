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
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/help.css"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/step.css"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/style.css"/>

    <script src="<?= Yii::app()->theme->baseUrl ?>/js/owl.carousel.min.js"></script>
    <script src="<?= Yii::app()->theme->baseUrl ?>/js/wow.min.js"></script>
    <script src="<?= Yii::app()->theme->baseUrl ?>/js/main.js"></script>
</head>
<body>
<div id="wrapper" class="no-pad">
    <div id="main-content" class="">
        <?php echo $content ?>
    </div>
</div>
<!-- End wrapper -->
</body>
</html>
