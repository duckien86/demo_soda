<?php
$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Change Password");
$this->breadcrumbs = array(
    UserModule::t("Profile") => array('/user/profile'),
    UserModule::t("Change Password"),
);
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo UserModule::t("Change password"); ?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">  
                <?php //echo $this->renderPartial('menu');  ?>

                <div class="form">
                    <?php
                    $form = $this->beginWidget('UActiveForm', array(
                        'id' => 'changepassword-form',
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array(
                            'class' => 'form-horizontal form-label-left',
                        ),
                    ));
                    ?>

                    <span class="section"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></span>

                    <?php echo CHtml::errorSummary($model); ?>

                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'password', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $form->passwordField($model, 'password', array('class' => 'form-control col-md-7 col-xs-12')); ?>
                            <ul class="parsley-errors-list">
                                <li><?php echo $form->error($model, 'password', array('class' => 'parsley-required')); ?></li>
                            </ul>
                            <p class="hint">
                                <?php echo UserModule::t("Minimal password length 4 symbols."); ?>
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'verifyPassword', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $form->passwordField($model, 'verifyPassword', array('class' => 'form-control col-md-7 col-xs-12')); ?>
                            <ul class="parsley-errors-list">
                                <li><?php echo $form->error($model, 'verifyPassword', array('class' => 'parsley-required')); ?></li>
                            </ul>
                        </div>
                    </div>


                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <?php echo CHtml::submitButton(UserModule::t("Save"), array('class' => 'btn btn-success')); ?>
                        </div>
                    </div>

                    <?php $this->endWidget(); ?>
                </div><!-- form -->
            </div>
        </div>
    </div>
</div>