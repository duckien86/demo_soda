<?php
/**
 * @var $this OrderController
 */

$this->pageTitle = 'Freedoo - ' . Yii::t('tourist/label', 'freedoo_tourist') . ' - ' . Yii::t('tourist/label', 'restore_password');

?>
<div id="forgetpassword" class="container text-center">
    <div class="modal-forgetpassword">
        <div class="form-title">
            <img src="<?php echo Yii::app()->theme->baseUrl . '/images/icon_login_title.png'?>"/>
        </div>
        <div class="form-title-description">
            <h4><b><?php echo CHtml::encode(Yii::t('tourist/label', 'change_password_success'))?></b></h4>
        </div>

        <div class="form-group">
            <a id="btn-back_to_login" href="<?php echo Yii::app()->createUrl('user/login');?>">
                <?php echo Yii::t('tourist/label', 'back_to_login'); ?>
            </a>
        </div>
    </div>
</div>