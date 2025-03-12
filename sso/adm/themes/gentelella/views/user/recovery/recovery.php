<?php
$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Restore");
$this->breadcrumbs = array(
    UserModule::t("Login") => array('/user/login'),
    UserModule::t("Restore"),
);
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo UserModule::t("Restore"); ?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content"> 
                <?php if (Yii::app()->user->hasFlash('recoveryMessage')): ?>
                    <div class="alert alert-success alert-dismissible fade in">
                        <button aria-label="<?php echo Yii::t('app', 'Close') ?>" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
                        <?php echo Yii::app()->user->getFlash('recoveryMessage'); ?>
                    </div>
                <?php else: ?>
                    <div class="form">
                        <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal form-label-left')); ?>
                        <?php echo CHtml::errorSummary($form); ?>

                        <div class="form-group">
                            <?php echo CHtml::activeLabel($form, 'login_or_email', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo CHtml::activeTextField($form, 'login_or_email', array('class' => 'form-control col-md-7 col-xs-12')) ?>
                                <p class="hint"><?php echo UserModule::t("Please enter your login or email addres."); ?></p>
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <?php echo CHtml::submitButton(UserModule::t("Restore"), array('class' => 'btn btn-success')); ?>
                            </div>
                        </div>

                        <?php echo CHtml::endForm(); ?>
                    </div><!-- form -->
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>