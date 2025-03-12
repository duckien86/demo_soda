<?php
    $version = '?v=1.1';
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
    $cs->registerCssFile($themePath . '/css/custom.css' . $version);
    $cs->registerCssFile($themePath . '/css/report.css' . $version);

    //calendar
    $cs->registerCssFile($themePath . '/css/calendar/fullcalendar.css');

    $cs->registerCssFile($themePath . '/css/icheck/flat/green.css');
    $cs->registerCssFile($themePath . '/css/sim.css' . $version);
    //    $cs->registerCssFile($themePath . '/css/style_search_sim.css');
    $cs->registerCssFile($themePath . '/css/style.css' . $version);

    $cs->registerCssFile($themePath . '/css/step.css' . $version);

    /**
     * JavaScripts
     */
    $cs->registerCoreScript('jquery', CClientScript::POS_HEAD);

    //bootstrap progress js
    $cs->registerScriptFile($themePath . '/js/custom.js' . $version, CClientScript::POS_END);
    $cs->registerScriptFile($themePath . '/js/jtabber.js', CClientScript::POS_END);
    //    $cs->registerScriptFile($themePath . '/js/progressbar/bootstrap-progressbar.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($themePath . '/js/nicescroll/jquery.nicescroll.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($themePath . '/js/icheck/icheck.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($themePath . '/js/jquery.battatech.excelexport.js', CClientScript::POS_END);
    $cs->registerScriptFile($themePath . '/js/owl.carousel.min.js', CClientScript::POS_END);
    //calendar
    $cs->registerScriptFile($themePath . '/js/main.js' . $version, CClientScript::POS_END);
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
    <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/moment.min2.js"></script>
    <script type="text/javascript"
            src="<?php echo Yii::app()->theme->baseUrl; ?>/js/calendar/fullcalendar.min.js"></script>
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

            <?php if (isset($this->breadcrumbs)){
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
            } ?>
            <?php $this->widget('booster.widgets.TbAlert'); ?>
            <?php echo $content; ?>
            <?php
            if(isset(Yii::app()->session['orders_data']) && isset(Yii::app()->session['orders_data']->orders)){
                $this->renderPartial('//layouts/_modal_warning');
            }
            ?>
            <?php $this->renderPartial('//layouts/_modal_alert'); ?>
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
<script type="text/javascript">

</script>