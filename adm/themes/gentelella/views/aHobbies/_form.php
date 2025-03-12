<?php
    /* @var $this ANewsController */
    /* @var $model ANews */
    /* @var $form CActiveForm */
?>

<div class="container-fluid">
    <div class="form">
        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                   => 'ahobbies-form',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => FALSE,
        )); ?>

        <div class="col-md-12">
            <?= Yii::t('adm/actions', 'required_field') ?>
        </div>
        <div class="col-md-12">
            <?php echo $form->errorSummary($model); ?>
        </div>
        <div class="col-md-4 col-xs-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'name', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'name', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'index_order', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'index_order', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'index_order'); ?>
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
                    </label>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-xs-12">
            <div class="form-group buttons">
		<span class="btnintbl">
            <span class="icondk">
                <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('adm/actions', 'create') : Yii::t('adm/actions', 'save'), array('class' => 'btn btn-success')); ?>
            </span>
        </span>
            </div>
        </div>

        <?php $this->endWidget(); ?>

    </div>
    <!-- End thumbnail modal -->
