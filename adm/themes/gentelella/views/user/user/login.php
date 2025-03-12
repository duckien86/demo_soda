<?php
    $this->pageTitle   = Yii::app()->name . ' - ' . UserModule::t("Login");
    $this->breadcrumbs = array(
        UserModule::t("Login"),
    );
?>
<div class="login-title text-center">
    <h2 class="title-info">ĐĂNG NHẬP</h2>
</div>
<div class="form">
    <?php echo CHtml::beginForm(); ?>
    <div class="container ">
        <div class="row main-login main-center">
            <?php if (Yii::app()->user->hasFlash('loginMessage')): ?>
                <div class="alert alert-success alert-dismissible fade in">
                    <button aria-label="<?php echo Yii::t('app', 'Close') ?>" data-dismiss="alert" class="close"
                            type="button"><span aria-hidden="true">×</span></button>
                    <?php echo Yii::app()->user->getFlash('loginMessage'); ?>
                </div>
            <?php endif; ?>
            <br/>
            <div class="form-group">
                <?php echo CHtml::activeTextField($model, 'username', array('class' => 'form-control form-design', 'placeholder' => "Tên đăng nhập")) ?>
            </div>

            <div class="form-group">
                <?php echo CHtml::activePasswordField($model, 'password', array('class' => 'form-control form-design', 'placeholder' => "Mật khẩu")) ?>
            </div>
<!--            --><?php //if (isset($type) && $type == 1): ?>
                <div class="form-group">
                    <?php echo CHtml::activeTextField($model, 'phone', array('class' => 'form-control form-design', 'placeholder' => "Số điện thoại")) ?>
                </div>
<!--            --><?php //endif; ?>
            <?php echo CHtml::errorSummary($model); ?>
            <div class="form-group remember-forget">
                <div class="remember-login">
                    <?php echo CHtml::activeCheckBox($model, 'rememberMe'); ?>
                    <?php echo CHtml::activeLabelEx($model, 'rememberMe'); ?>
                    <?php echo CHtml::link(UserModule::t("Lost Password?"), Yii::app()->getModule('user')->recoveryUrl, array('class' => 'reset_pass')); ?>
                </div>
                <div class="login-element-form">
                    <div class="login-register">
                        <?php echo CHtml::submitButton(UserModule::t("Login"), array('class' => 'btn btn-primary btn-lg btn-block login-button button-sso-login')); ?>

                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
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
