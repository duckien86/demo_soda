<!DOCTYPE html>
<html>
<head>
    <meta id="viewport" name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="UTF-8"/>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/style.css"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/home.css"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/left-nav.css"/>
    <link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/help.css"/>
    <link rel="stylesheet" type="text/css" href="<?= Yii::app()->theme->baseUrl ?>/css/owl.carousel.min.css">
	<link media="screen" rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/css/step.css"/>


    <script src="<?= Yii::app()->theme->baseUrl ?>/js/main.js"></script>
    <script src="<?= Yii::app()->theme->baseUrl ?>/js/jquery.mmenu.min.all.js"></script>
    <script src="<?= Yii::app()->theme->baseUrl ?>/js/owl.carousel.min.js"></script>
</head>
<body>
<div id="wrapper" class="no-pad">
    <?php $this->renderPartial('/layouts/_mobile_header'); ?>
    <?php $this->renderPartial('/layouts/_mobile_menu'); ?>

    <div id="main-content" class="">
        <?php echo $content ?>
    </div>
    <!-- End id main-content -->
    <?php $this->renderPartial('//layouts/_main_footer'); ?>
</div>
<!-- End wrapper -->
<a href="#" class="scrollToTop"></a>
</body>
</html>
