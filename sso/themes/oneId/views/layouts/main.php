<?php
    define('DAILY_PACKAGE', 199);
    define('VIETTEL_TELCO', 'VIETTEL');
    $cs        = Yii::app()->clientScript;
    $theme_url = $this->theme_url;
    $cs->registerCssFile($theme_url . '/css/form_style.css');
    $cs->registerCssFile($theme_url . '/css/font-awesome.min.css');

//    $cs->registerCssFile($theme_url . '/css/lightslider.css');// slide nằm ngang
    $cs->registerCssFile($theme_url . '/css/news-style.css');// css by duong nv
    $cs->registerCssFile($theme_url . '/css/style-mobile.css');//Main css    $cs->registerCssFile($theme_url . '/css/left-nav.css');

    $cs->registerCoreScript('jquery', CClientScript::POS_HEAD);
//    $cs->registerScriptFile($theme_url . '/js/jquery.mmenu.min.all.js', CClientScript::POS_HEAD);
//    $cs->registerScriptFile($theme_url . '/js/lightslider.min.js', CClientScript::POS_HEAD);
//    $cs->registerScriptFile($theme_url . '/js/jwplayer/jwplayer.js', CClientScript::POS_HEAD);
//    $cs->registerScriptFile($theme_url . '/js/mobile.js', CClientScript::POS_HEAD);

?>
<!DOCTYPE html>
<html>
<head>
    <meta id="viewport" name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="UTF-8"/>
    <title><?php echo $this->pageTitle; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <script type="application/x-javascript"> addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);
        function hideURLbar() {
            window.scrollTo(0, 1);
        } </script>
    <!-- Custom Theme files -->
    <link href="<?=$theme_url?>/css/style.css" rel="stylesheet" type="text/css" media="all"/>
    <!-- //Custom Theme files -->
    <!-- web font -->
    <link href="//fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic'
          rel='stylesheet' type='text/css'>
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
    <script src="<?= $theme_url ?>/js/jquery.cookie.js"></script>
</head>
<script>
    $(document).bind("mobileinit", function () {
        //Disable Ajax Load data when click a Link
        $.mobile.ajaxEnabled = false;
    });
</script>
<body>

<div class="main">
    <?= $content ?>
</div>
<div class="copyright-w3-agile">
    <p> © 2017 OneID . All rights reserved
    </p>
</div>

</body>
</html>

