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
            <h4><b><?php echo CHtml::encode(Yii::t('tourist/label', 'send_token_success'))?></b></h4>
        </div>
    </div>
</div>
