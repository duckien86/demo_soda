<?php
$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Login");
$this->breadcrumbs = array(
    UserModule::t("Login"),
);
?>

<div class="form">
    <?php echo CHtml::beginForm(); ?>
    <h1><?php echo UserModule::t("Login"); ?></h1>

    <?php if (Yii::app()->user->hasFlash('loginMessage')): ?>

        <div class="alert alert-success alert-dismissible fade in">
            <button aria-label="<?php echo Yii::t('app', 'Close') ?>" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
            <?php echo Yii::app()->user->getFlash('loginMessage'); ?>
        </div>

    <?php endif; ?>

    <p><?php echo UserModule::t("Please fill out the following form with your login credentials:"); ?></p>

    <p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

    <?php echo CHtml::errorSummary($model); ?>

    <div class="row">
        <?php echo CHtml::activeTextField($model, 'username', array('class' => 'form-control', 'placeholder' => UserModule::t("Username"))) ?>
    </div>

    <div class="row">
        <?php echo CHtml::activePasswordField($model, 'password', array('class' => 'form-control', 'placeholder' => UserModule::t("Password"))) ?>
    </div>

    <div class="row rememberMe">
        <?php echo CHtml::activeCheckBox($model, 'rememberMe'); ?>
        <?php echo CHtml::activeLabelEx($model, 'rememberMe'); ?>
    </div>

    <div class="row submit">
        <?php echo CHtml::submitButton(UserModule::t("Login"), array('class' => 'btn btn-default')); ?>
        <?php echo CHtml::link(UserModule::t("Lost Password?"), Yii::app()->getModule('user')->recoveryUrl, array('class' => 'reset_pass')); ?>
    </div>

    <div class="clearfix"></div>
    <div class="separator">
        <div class="clearfix"></div>
        <br />
        <div>
            <p>Copyright &copy; <?php echo date('Y'); ?> by <?php echo CHtml::encode(Yii::app()->name); ?>. All Rights Reserved.</p>
        </div>
    </div>
    <?php echo CHtml::endForm(); ?>
</div><!-- form -->


<?php
$form = new CForm(array(
    'elements' => array(
        'username' => array(
            'type' => 'text',
            'maxlength' => 32,
        ),
        'password' => array(
            'type' => 'password',
            'maxlength' => 32,
        ),
        'rememberMe' => array(
            'type' => 'checkbox',
        )
    ),
    'buttons' => array(
        'login' => array(
            'type' => 'submit',
            'label' => 'Login',
        ),
    ),
        ), $model);
?>