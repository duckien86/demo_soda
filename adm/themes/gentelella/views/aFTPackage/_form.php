<?php
    /* @var $this AFTPackageController */
    /* @var $model AFTPackage */
    /* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'                   => 'aftpackage-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => FALSE,
    )); ?>

    <div class="col-md-12">
        <p class="note"><?= Yii::t('adm/actions', 'required_field') ?></p>

        <?php echo $form->errorSummary($model); ?>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'name', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'name', array('class' => 'textbox', 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'name'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'code', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'code', array('class' => 'textbox', 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'code'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'price', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->numberField($model, 'price', array('class' => 'textbox', 'maxlength' => 10, 'onkeyup' => 'formatNumber(this);')); ?>
            <?php echo $form->error($model, 'price'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'type', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->dropDownList($model, 'type', AFTPackage::getListType(), array('class' => 'form-control'));?>
            <?php echo $form->error($model, 'type'); ?>
        </div>
        <div class="form-group">
            <div class="checkbox-nopad">
                <label>
                    <?php echo $form->checkBox($model, 'is_bundle', array('class' => 'flat')) . ' ' . Yii::t('adm/label', 'is_bundle');?>
                </label>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'description', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textArea($model, 'description', array('class' => 'textarea', 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'description'); ?>
        </div>
        <div class="form-group">
            <div class="checkbox-nopad">
                <label>
                    <?php
                        if ($model->isNewRecord) {
                            echo $form->checkBox($model, 'status', array('checked' => 'checked', 'class' => 'flat')) . ' ' . Yii::t('adm/label', 'active');
                        } else {
                            echo $form->checkBox($model, 'status', array('class' => 'flat')) . ' ' . Yii::t('adm/label', 'active');
                        }
                    ?>
                    &nbsp;&nbsp;&nbsp;</label>
            </div>
        </div>
        <div class="form-group buttons">
		        <span class="btnintbl">
                    <span class="icondk">
                        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('adm/actions', 'create') : Yii::t('adm/actions', 'save'), array('class' => 'btn btn-success')); ?>
                    </span>
                </span>
        </div>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->