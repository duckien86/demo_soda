<!DOCTYPE html>
<html>
<head>
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="UTF-8"/>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="shortcut icon" href="<?= Yii::app()->theme->baseUrl ?>/images/fav.ico" type="image/x-icon">
    <!-- font-awesome -->
    <link href="<?= Yii::app()->theme->baseUrl ?>/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= Yii::app()->theme->baseUrl ?>/css/home.css" rel="stylesheet">
    <link href="<?= Yii::app()->theme->baseUrl ?>/css/style.css" rel="stylesheet">
    <link href="<?= Yii::app()->theme->baseUrl ?>/css/login.css" rel="stylesheet">
    <link href="<?= Yii::app()->theme->baseUrl ?>/css/forget-pass.css" rel="stylesheet">
</head>
<body>
<div id="wrapper" class="no-pad">
    <?php $this->renderPartial('/layouts/_main_header'); ?>
    <div id="main-content" class="">
        <?php echo $content ?>
    </div>
    <!-- End id main-content -->
</div>
<!-- End wrapper -->
</body>
</html>
