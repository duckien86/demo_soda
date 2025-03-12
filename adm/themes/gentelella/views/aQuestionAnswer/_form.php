<?php
    /* @var $this AQuestionAnswerController */
    /* @var $model AQuestionAnswer */
    /* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'                   => 'aquestion-answer-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => FALSE,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'question'); ?>
                <?php echo $form->textArea($model, 'question', array('size' => 60, 'maxlength' => 2000, 'rows' => 6, 'cols' => 6, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'question'); ?>
            </div>
        </div>

        <div class="col-md-6 col-xs-12">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'answer'); ?>
                <?php echo $form->textArea($model, 'answer', array('size' => 60, 'maxlength' => 2000, 'rows' => 6, 'cols' => 6, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'answer'); ?>
            </div>
        </div>

        <div class="col-md-6 col-xs-12">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'cate_qa_id'); ?>
                <?php echo $form->dropDownList($model, 'cate_qa_id', ACategoryQa::getAllCateQa(), array('prompt' => 'Chọn danh mục', 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'cate_qa_id'); ?>
            </div>
        </div>

        <div class="col-md-12 col-xs-12">
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
    <div class="row buttons" style="margin-left: 0px;">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Tạo mới' : 'Cập nhật', array('class' => 'btn btn-success')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->