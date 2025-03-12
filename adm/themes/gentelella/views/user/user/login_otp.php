<div class="form">
    <?php echo CHtml::beginForm(); ?>
    <?php echo CHtml::errorSummary($model); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <h5> * Nhập mã xác thực OTP của bạn (<?= $otp ?>)</h5>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <?php echo CHtml::activeTextField($model, 'otp', array('class' => 'form-control form-design', 'placeholder' => "Nhập mã xác thực của bạn")) ?>
            <div class="input-group-addon">
                <?php echo CHtml::link('<i class="fa fa-refresh"></i>',
                    Yii::app()->createUrl('user/login/resendOtp', array(
                        'data' => $_GET['data']
                    )),
                    array(
                        'id'                => 'btnResendOtp',
                        'data-toggle'       => 'tooltip',
                        'title'             => 'Gửi lại OTP',
                        'onclick'           => '$(this).addClass("disabled").css("cursor","no-drop")'
                    )
                )?>
            </div>
        </div>
    </div>
    <?php echo CHtml::activeHiddenField($model, 'username', array('class' => 'form-control form-design', 'placeholder' => UserModule::t("Username"))) ?>
    <?php echo CHtml::activeHiddenField($model, 'password', array('class' => 'form-control', 'placeholder' => UserModule::t("Password"))) ?>
    <?php echo CHtml::activeHiddenField($model, 'phone', array('class' => 'form-control', 'placeholder' => UserModule::t("Phone"))) ?>
    <input type="hidden" name="UserLogin[rememberMe]" value="<?= $model->rememberMe ?>">

    <div class="form-group">
        <h6> (Mã OTP sẽ được duy trì trong một ngày, để gửi lại OTP vui lòng bấm nút gửi lại)</h6>
    </div>

    <div class="login-element-form">
        <div class="login-register">
            <?php echo CHtml::submitButton('Xác thực', array('class' => 'btn btn-primary btn-lg btn-block login-button button-sso-login')); ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php echo CHtml::endForm(); ?>
</div><!-- form -->


<?php
    $form = new CForm(array(
        'elements' => array(
            'username'   => array(
                'type'      => 'text',
                'maxlength' => 32,
            ),
            'password'   => array(
                'type'      => 'password',
                'maxlength' => 32,
            ),
            'rememberMe' => array(
                'type' => 'checkbox',
            )
        ),
        'buttons'  => array(
            'login' => array(
                'type'  => 'submit',
                'label' => 'Login',
            ),
        ),
    ), $model);
?>
<style>

    #login {
        color: #726e6e;
        background-color: white;
        font-size: 12px;
    }

    #login .form-control {
        margin: auto !important;
        background-color: #ededed;
        height: 40px;
        box-shadow: 0px 0px !important;
        border-radius: 0px !important;
    }

    #login .form-fix {
        margin-bottom: 20px !important;
        width: 85%;
        margin: auto;
    }

    .form-fix .input-group-addon {
        width: 50px;
        background-color: #cecece;
    }

    .form-fix img {
        width: 70%;
    }

    .form-fix input {
        float: left;
    }

    .form-fix .btn {
        background-color: #bb2fa1;
        border-radius: 0px !important;
        color: white;
        height: 40px;
        width: 150px;
        margin: auto;
    }

    .form-fix label {
        float: left;
        margin-left: 5px;
        line-height: 22px;
    }

    .green {
        background-color: #34c8f1 !important;
    }

    .separator {
        width: 80%;
        margin: auto
    }

    .login_content {
        padding: 0 !important;
    }

    .form-fix .form-control::placeholder {
        color: #726e6e;
    }

</style>