<?php
$cs = Yii::app()->clientScript;
$themePath = Yii::app()->theme->baseUrl;

/**
 * StyleSHeets
 */
//Bootstrap core CSS
$cs->registerCssFile($themePath . '/css/bootstrap.min.css');
$cs->registerCssFile($themePath . '/fonts/css/font-awesome.min.css');
$cs->registerCssFile($themePath . '/css/animate.min.css');

//Custom styling plus plugins
$cs->registerCssFile($themePath . '/css/custom.css');
$cs->registerCssFile($themePath . '/css/icheck/flat/green.css');

/**
 * JavaScripts
 */
$cs->registerCoreScript('jquery', CClientScript::POS_HEAD);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
              <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
              <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <![endif]-->

    </head>

    <body style="background:#F7F7F7;">
        <div class="">
            <a class="hiddenanchor" id="toregister"></a>
            <a class="hiddenanchor" id="tologin"></a>

            <div id="wrapper">
                <div id="login" class="animate form">
                    <section class="login_content">
                        <?php echo $content; ?>
                    </section>
                </div>
            </div>
        </div>
    </body>
</html>