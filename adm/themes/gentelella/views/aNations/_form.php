<?php
    /* @var $this ANationsController */
    /* @var $model ANations */
    /* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'                   => 'anations-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => FALSE,
        'htmlOptions'          => array('enctype' => 'multipart/form-data')
    )); ?>

    <div class="form-group">
        <?= Yii::t('adm/actions', 'required_field') ?>
    </div>
    <?php echo $form->errorSummary($model); ?>

    <div class="col-md-5">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'name', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'name', array('class' => 'textbox', 'size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'name'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'code', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'code', array('class' => 'textbox', 'size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'code'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'telco_prepaid', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textArea($model, 'telco_prepaid', array('class' => 'textarea', 'cols' => 50)); ?>
            <?php echo $form->error($model, 'telco_prepaid'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'telco_postpaid', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textArea($model, 'telco_postpaid', array('class' => 'textarea', 'cols' => 50)); ?>
            <?php echo $form->error($model, 'telco_postpaid'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'continent', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->dropDownList($model, 'continent', $model->arrayContinent(), array('prompt' => Yii::t('adm/label', 'select'), 'class' => 'dropdownlist')); ?>
            <?php echo $form->error($model, 'continent'); ?>
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
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('adm/actions', 'create') : Yii::t('adm/actions', 'save'), array('class' => 'btn btn-success')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->