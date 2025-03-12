<?php
    $cs        = Yii::app()->clientScript;
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

    //calendar
    $cs->registerCssFile($themePath . '/css/calendar/fullcalendar.css');

    $cs->registerCssFile($themePath . '/css/main.css');
    $cs->registerCssFile($themePath . '/css/style.css');

    /**
     * JavaScripts
     */
    $cs->registerCoreScript('jquery', CClientScript::POS_HEAD);

    //bootstrap progress js
    $cs->registerScriptFile($themePath . '/js/progressbar/bootstrap-progressbar.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($themePath . '/js/nicescroll/jquery.nicescroll.min.js', CClientScript::POS_END);

    //icheck
    $cs->registerScriptFile($themePath . '/js/icheck/icheck.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($themePath . '/js/custom.js', CClientScript::POS_END);
    //calendar
    $cs->registerScriptFile($themePath . '/js/moment.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($themePath . '/js/calendar/fullcalendar.min.js', CClientScript::POS_END);
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

<body class="nav-md">

<div class="container body">
    <div class="main_container">
        <?php
            $this->beginContent('//layouts/left_col');
            $this->endContent();
        ?>

        <!-- top navigation -->
        <?php
            $this->beginContent('//layouts/top_nav');
            $this->endContent();
        ?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <?php if (isset($this->breadcrumbs)): ?>
                    <?php
                    $this->widget('zii.widgets.CBreadcrumbs', array(
                        'links' => $this->breadcrumbs,
                    ));
                    ?><!-- breadcrumbs -->
                <?php endif ?>
                <?php $this->widget('booster.widgets.TbAlert'); ?>
                <?php echo $content; ?>
            </div>

            <footer>
                <div class="">
                    <p class="pull-right">Copyright &copy; <?php echo date('Y'); ?>
                        by <?php echo CHtml::encode(Yii::app()->name); ?>. All Rights Reserved.
                        <!--                        | <span class="lead"> <i class="fa fa-paw"></i> -->
                        <?php //echo Yii::powered(); ?><!--</span>-->
                    </p>
                </div>
            </footer>
            <!-- footer -->
        </div>
        <!-- /page content -->
    </div>
</div>
<!-- page -->
</body>
</html>
