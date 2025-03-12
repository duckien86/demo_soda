<?php if (isset(Yii::app()->user->customer_id) && Yii::app()->user->customer_id != '') {
    $url = '#menu_right';
} else {
    $url = $GLOBALS['config_common']['domain_sso']['sso'] . $GLOBALS['config_common']['domain_sso']['pid'];
} ?>
<div class="main_header">
    <div class="container">
        <div class="fl">
            <a href="#menu" class="icon_menu">
                <img src="<?= Yii::app()->theme->baseUrl; ?>/images/menu_mobile.png">
            </a>

            <div class="logo">
                <a href="<?= Yii::app()->controller->createUrl('site/index'); ?>">
                    <img src="<?= Yii::app()->theme->baseUrl; ?>/images/logo_freedoo.png" class="logo">
                </a>
            </div>
        </div>
        <div class="fr">
            <a href="<?= $url ?>">
                <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon-user-mobile.png">
            </a>
        </div>
    </div>
</div>
<div class="header_line"></div>

<div id="worldcup_topmenu">
    <div class="banner_worldcup">
        <img src="<?php echo Yii::app()->theme->baseUrl?>/images/banner_worldcup.jpg">
    </div>
</div>
