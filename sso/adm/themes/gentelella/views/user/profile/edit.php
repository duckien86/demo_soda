<?php
$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Profile");
$this->breadcrumbs = array(
    UserModule::t("Profile") => array('profile'),
    UserModule::t("Edit"),
);
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo UserModule::t('Edit profile'); ?></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">            
                <?php //echo $this->renderPartial('menu');  ?>

                <?php if (Yii::app()->user->hasFlash('profileMessage')): ?>
                    <div class="alert alert-success alert-dismissible fade in">
                        <button aria-label="<?php echo Yii::t('app', 'Close') ?>" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
                        <?php echo Yii::app()->user->getFlash('profileMessage'); ?>
                    </div>
                <?php endif; ?>
                <div class="form">
                    <?php
                    $form = $this->beginWidget('UActiveForm', array(
                        'id' => 'profile-form',
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array(
                            'class' => 'form-horizontal form-label-left',
                            'enctype' => 'multipart/form-data',
                        ),
                    ));
                    ?>

                    <span class="section"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></span>

                    <?php echo $form->errorSummary(array($model, $profile)); ?>

                    <?php
                    $profileFields = $profile->getFields();
                    if ($profileFields) {
                        foreach ($profileFields as $field) {
                            ?>
                            <div class="form-group">
                                <?php
                                echo $form->labelEx($profile, $field->varname, array('class' => 'control-label col-md-3 col-sm-3 col-xs-12'));

                                echo '<div class="col-md-6 col-sm-6 col-xs-12">';
                                if ($field->widgetEdit($profile)) {
                                    echo $field->widgetEdit($profile);
                                } elseif ($field->range) {
                                    echo $form->dropDownList($profile, $field->varname, Profile::range($field->range));
                                } elseif ($field->field_type == "TEXT") {
                                    echo $form->textArea($profile, $field->varname, array('class' => 'resizable_textarea form-control', 'rows' => 6, 'cols' => 50));
                                } else {
                                    echo $form->textField($profile, $field->varname, array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => (($field->field_size) ? $field->field_size : 255)));
                                }
                                echo '<ul class="parsley-errors-list"><li>' . $form->error($profile, $field->varname, array('class' => 'parsley-required')) . '</li></ul>';
                                echo '</div>';
                                ?>
                            </div>	
                            <?php
                        }
                    }
                    ?>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'username', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $form->textField($model, 'username', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 20, 'maxlength' => 20)); ?>
                            <ul class="parsley-errors-list">
                                <li><?php echo $form->error($model, 'username', array('class' => 'parsley-required')); ?></li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'email', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $form->textField($model, 'email', array('class' => 'form-control col-md-7 col-xs-12', 'size' => 60, 'maxlength' => 128)); ?>
                            <ul class="parsley-errors-list">
                                <li><?php echo $form->error($model, 'email', array('class' => 'parsley-required')); ?></li>
                            </ul>
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <?php echo CHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save'), array('class' => 'btn btn-success')); ?>
                        </div>
                    </div>

                    <?php $this->endWidget(); ?>

                </div><!-- form -->
            </div>
        </div>
    </div>
</div>