<?php if (isset(Yii::app()->user->customer_id) && Yii::app()->user->customer_id != '') {
    $url = '#menu_right';
} else {
    $url = $GLOBALS['config_common']['domain_sso']['sso'] . $GLOBALS['config_common']['domain_sso']['pid'];
} ?>
<div class="main_header">
    <div class="container">
        <div class="fl">
            <a href="#menu" class="icon_menu">
                <img src="<?= Yii::app()->theme->baseUrl; ?>/mobile_images/menu_mobile.png">
            </a>

            <div class="logo">
                <a href="<?= Yii::app()->controller->createUrl('site/index'); ?>">
                    <img src="<?= Yii::app()->theme->baseUrl; ?>/mobile_images/logo_telmall.png" class="logo">
                </a>
            </div>
        </div>
        <div class="fr">
            <a href="<?= $url ?>">
                <img src="<?= Yii::app()->theme->baseUrl; ?>/mobile_images/icon-log-new.png">
            </a>
        </div>
    </div>
</div>
<div class="header_line"></div>
