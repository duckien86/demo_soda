<?php
    /* @var $this ACategoryQaController */
    /* @var $model ACategoryQa */
    /* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'                   => 'acategory-qa-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => FALSE,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'name'); ?>
                <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
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
    </div>

    <div class="row buttons" style="margin-left:0px;">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Tạo mơi' : 'Cập nhật', array('class' => 'btn btn-success')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->