<?php
//if (isset(Yii::app()->user->customer_id) && Yii::app()->user->customer_id != '') {
//    $url = '#menu_right';
//} else {
//    $url = $GLOBALS['config_common']['domain_sso']['sso'] . $GLOBALS['config_common']['domain_sso']['pid'];
//}
?>
<div class="main_header">
    <div class="container">
        <div class="fl">
            <a href="#menu" class="icon_menu">
                <img src="<?= Yii::app()->theme->baseUrl; ?>/images/menu_mobile.png">
            </a>

            <div class="logo">
                <a href="<?= Yii::app()->controller->createUrl('site/index'); ?>">
                    <img src="<?= Yii::app()->theme->baseUrl; ?>/images/logo_freedoo_tourist.png" class="logo">
                </a>
            </div>
        </div>
        <div class="fr">
            <a href="#menu_right" title="">
                <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon-user-mobile.png">
            </a>
        </div>
    </div>
</div>
<div class="header_line"></div>
