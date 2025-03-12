<?php
/**
 * @var UserController $this
 * @var TLoginForm $model
 */
$this->pageTitle = 'Freedoo - ' . Yii::t('tourist/label', 'freedoo_tourist') . ' - ' . Yii::t('tourist/label', 'login');
$btn_class = 'btn btn-lg';
if($this->isMobile) $btn_class = 'btn';
?>
<div id="login" class="container text-center">
    <div id="chooseLogin">
        <div class="modal-login">
            <div class="form-title">
                <img src="<?php echo Yii::app()->theme->baseUrl . '/images/icon_login_title.png'?>"/>
            </div>
            <div class="form-title-description">
                <p><i>Chọn kiểu đăng nhập</i></p>
            </div>
            <div class="form-group text-center">
                <?php echo CHtml::link('<i class="fa fa-user"></i> Khách hàng cá nhân', Yii::app()->createUrl('user/loginCtv'), array(
                    'class' => 'btn btn-info',
                    'style' => 'width: 220px',
                ));?>
            </div>

            <div class="form-group text-center">
                <?php echo CHtml::link('<i class="fa fa-users"></i> Khách hàng Doanh nghiệp', Yii::app()->createUrl('user/login'), array(
                    'class' => 'btn btn-primary',
                    'style' => 'width: 220px',
                ));?>
            </div>
        </div>
    </div>
</div>