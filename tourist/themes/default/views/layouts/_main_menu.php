<?php
$controller = Yii::app()->controller->id;
$action     = strtolower(Yii::app()->controller->action->id);
//if (isset(Yii::app()->user->sso_id) && Yii::app()->user->sso_id != '') {
//    $data = array(
//        'user_id' => Yii::app()->user->sso_id,
//    );
//    $data = http_build_query($data);
//    $data = Utils::encrypt($data, Yii::app()->params['aes_key'], MCRYPT_RIJNDAEL_128);
//
//    $url_changepass = 'http://' . SERVER_HTTP_HOST . '/sso/changepass/001?data=' . $data;
//}

?>
<div class="main_menu" style="margin-bottom: 15px">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <?php if(isset($this->breadcrumbs)){
                    $this->widget('zii.widgets.CBreadcrumbs', array(
                        'links' => $this->breadcrumbs,
                        'separator' => '<i>&rarr;</i>',
                    ));
                }
                ?>
            </div>
            <div class="col-sm-6">
                <div class="fr">
                    <div class="info welcome-user">
                        <?php echo Yii::t('tourist/label', 'hello') . ':' ?>
                        <span class="user"><?php echo Yii::app()->user->name ?></span>
                    </div>
                    <div class="info icon-user menu_user">
                        <a href="<?= Yii::app()->controller->createUrl('user/info'); ?>">
                            <img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_user.png">
                        </a>
                        <ul class="sub_menu_user dropdown-menu">
                            <?php if(Yii::app()->user->user_type != TUsers::USER_TYPE_CTV){ ?>
                            <li>
                                <a href="<?= Yii::app()->controller->createUrl('user/info'); ?>" title="">
                                    <?php echo CHtml::encode(Yii::t('tourist/label', 'enterprises_info'))?>
                                </a>
                            </li>
<!--                                <li>-->
<!--                                    <a href="--><?//= ($url_changepass != '') ? $url_changepass : '' ?><!--" title="">-->
<!--                                        Đổi mật khẩu-->
<!--                                    </a>-->
<!--                                </li>-->
                            <li>
                                <a href="<?= Yii::app()->controller->createUrl('user/changePassword'); ?>" title="">
                                    <?php echo CHtml::encode(Yii::t('tourist/label', 'change_password'))?>
                                </a>
                            </li>
                            <?php }?>

                            <li>
                                <a href="<?= Yii::app()->controller->createUrl('user/logout'); ?>" title="">
                                    <?php echo CHtml::encode(Yii::t('tourist/label', 'logout'))?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>