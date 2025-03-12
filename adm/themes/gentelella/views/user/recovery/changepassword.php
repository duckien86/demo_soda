<?php $this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Change Password");
    $this->breadcrumbs = array(
        UserModule::t("Login") => array('/user/login'),
        UserModule::t("Change Password"),
    );
?>

<div class="x_panel container-fluid">
    <div class="x_title">
        <h2><?php echo "Lấy lại mật khẩu" ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">

        <div class="form">
            <?php echo CHtml::beginForm(); ?>

            <p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
            <?php echo CHtml::errorSummary($form); ?>
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <?php echo CHtml::activeLabelEx($form, 'password'); ?>
                        <?php echo CHtml::activePasswordField($form, 'password', array('class' => 'form-control')); ?>
                        <p class="hint">
                            <?php echo UserModule::t("Minimal password length 4 symbols."); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <?php echo CHtml::activeLabelEx($form, 'verifyPassword'); ?>
                        <?php echo CHtml::activePasswordField($form, 'verifyPassword', array('class' => 'form-control')); ?>
                        <p class="hint">
                            <?php echo UserModule::t("Minimal password length 4 symbols."); ?>
                        </p>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <?php echo CHtml::submitButton(UserModule::t("Save"), array('class' => 'btn btn-success')); ?>
                </div>
            </div>

            <?php echo CHtml::endForm(); ?>
        </div><!-- form -->
    </div>
</div>
</div>