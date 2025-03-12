<script src='https://www.google.com/recaptcha/api.js'></script>
<div class="container">
    <div class="row main">
        <div class="login-heading">
            <div class="login-title text-center">
                <h1 class="title"><img src="<?= Yii::app()->theme->baseUrl; ?>/images/logo_sso.png"></h1>
            </div>
            <div class="login-error text-center">
                <h5 class="title-error"><?= $error ?></h5>
            </div>
        </div>
        <div class="row main-login main-center">
            <!--            <form class="form-horizontal" id='RegisterForm' method="post" action="#">-->
            <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                'id'                   => 'otp-form',
                'enableAjaxValidation' => TRUE,
                'htmlOptions'          => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left avatar-form')
            )); ?>

            <div class="form-group">
                <label for="name" class="cols-sm-2 control-label">Mời bạn nhập mã OTP</label>
                <div class="cols-sm-10">
                    <?php echo $form->textField($model, 'otp', array('class' => 'form-control form-design', 'placeholder' => 'Nhập mã OTP')); ?>
                    <input type="hidden" class="form-control" name="WOtpForm[user_id]" id="WOtpForm_user_id"
                           value="<?= isset($user_id) ? $user_id : 0 ?>"/>
                </div>
                <?php echo $form->error($model, 'otp'); ?>
            </div>
            <div class="login-element-form " style="margin-top: 10px;">
                <div class="login-register" style="width: 100%; margin:auto;">
                    <button class="btn btn-primary btn-lg btn-block login-button button-sso-login"
                            style="font-size: 14px;" type="submit">
                        Xác thực
                    </button>
                </div>

            </div>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>

