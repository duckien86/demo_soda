<?php
    /**
     * LAYOUT RIÊNG, BỎ BANNER TRÊN CÙNG
     */
    $cs        = Yii::app()->clientScript;
    $theme_url = $this->theme_url;

    $cs->registerCssFile($theme_url . '/css/bootstrap.css');
    $cs->registerCssFile($theme_url . '/css/font-awesome.min.css');

    $cs->registerCssFile($theme_url . '/css/style-mobile-manh.css');// css by manh nv
    $cs->registerCssFile($theme_url . '/css/style-mobile.css');//Main css
    $cs->registerCssFile($theme_url . '/css/left-nav.css');

    $cs->registerScriptFile($theme_url . '/js/jquery.min.js', CClientScript::POS_HEAD);
    $cs->registerCoreScript('jquery', CClientScript::POS_HEAD);
    $cs->registerScriptFile($theme_url . '/js/bootstrap.min.js', CClientScript::POS_HEAD);
    $cs->registerScriptFile($theme_url . '/js/jquery.mmenu.min.all.js', CClientScript::POS_HEAD);
    $cs->registerScriptFile($theme_url . '/js/jwplayer/jwplayer.js', CClientScript::POS_HEAD);
    $cs->registerScriptFile($theme_url . '/js/lightslider.min.js', CClientScript::POS_HEAD);
    $cs->registerScriptFile($theme_url . '/js/mobile.js', CClientScript::POS_HEAD);
?>
<!DOCTYPE html>
<html>
<head>
    <meta id="viewport" name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="UTF-8"/>
    <title><?php echo $this->pageTitle; ?></title>
    <script type="text/javascript">
        <?php $detect = new MyMobileDetect();?>
        var ServerUrl = "<?=Yii::app()->createAbsoluteUrl('site/index')?>";
        var PartnerUrl = "<?=Yii::app()->createAbsoluteUrl('site/index')?>";
        var isMobile = <?=($detect->isMobile() ? 1 : 0)?>;
        var jsLivescore_Ngay = '';
        var CSRF_TOKEN = '<?=Yii::app()->request->csrfToken?>';
    </script>
<!--    <script src="--><?//= $theme_url ?><!--/js/jquery.min.js"></script>-->
    <script src="<?= $theme_url ?>/js/jquery.cookie.js"></script>
</head>
<style type="text/css">
    body {
        background: url(<?= Yii::app()->theme->baseUrl ?>/images/page-404/bg.jpg) no-repeat 100% !important;
        /* background-size: 100%; */
        /*font-family: 'open_sanslight';*/
        /*font-size: 100%;*/
        /*margin-top: -20px;*/
        /*background-repeat: no-repeat;*/
        /*background-attachment: fixed;*/
        /*background-size: cover;*/
        /*padding: 100px 0;*/
        /*text-align: center;*/
    }
    #main-content {
        /* padding: 0 !important; */
         background: none !important;
    }
</style>
<script>
    $(document).bind("mobileinit", function () {
        //Disable Ajax Load data when click a Link
        $.mobile.ajaxEnabled = false;
    });
</script>
<body style="height: 100%">
<div id="wrapper" class="no-pad">
    <div id="main-content" class="content container-fluid no-pad">
        <?php echo $content ?>
    </div>
    <!-- End id main-content -->
<!--    --><?php //$this->renderPartial('/layouts/mobile/_main_footer'); ?>
</div>

<!-- End wrapper -->
<a href="#" class="scrollToTop"></a>
<!-- Load my customer js -->

<!--<script src="--><?//= $theme_url; ?><!--/js/mobile.js" type="text/javascript"></script>-->
<?php /*$this->renderPartial('/layouts/mobile/_modal_login_reg'); */ ?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.9&appId=114202945764370";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
</body>
</html>
