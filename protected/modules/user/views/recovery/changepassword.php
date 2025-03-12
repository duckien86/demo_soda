<?php $this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Change Password");
    $this->breadcrumbs = array(
        UserModule::t("Login") => array('/user/login'),
        UserModule::t("Change Password"),
    );
?>

<div class="x_panel container-fluid">
    <div class="x_title">
        <h2><?php echo UserModule::t("Create User"); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="row">
            <div class="col-md-8">


                <div class="form">
                    <?php echo CHtml::beginForm(); ?>

                    <p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
                    <?php echo CHtml::errorSummary($form); ?>

                    <div class="row">
                        <?php echo CHtml::activeLabelEx($form, 'password'); ?>
                        <?php echo CHtml::activePasswordField($form, 'password'); ?>
                        <p class="hint">
                            <?php echo UserModule::t("Minimal password length 4 symbols."); ?>
                        </p>
                    </div>

                    <div class="row">
                        <?php echo CHtml::activeLabelEx($form, 'verifyPassword'); ?>
                        <?php echo CHtml::activePasswordField($form, 'verifyPassword'); ?>
                    </div>


                    <div class="row submit">
                        <?php echo CHtml::submitButton(UserModule::t("Save")); ?>
                    </div>

                    <?php echo CHtml::endForm(); ?>
                </div><!-- form -->
            </div>
        </div>
    </div>
</div>