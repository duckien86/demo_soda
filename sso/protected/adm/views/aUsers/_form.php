<?php
    /* @var $this AUsersController */
    /* @var $model AUsers */
    /* @var $form CActiveForm */
?>
<style>
    label {
        margin-top: 5px;
    }
</style>
<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'                   => 'ausers-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => FALSE,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'username'); ?>
            <?php echo $form->textField($model, 'username', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'username'); ?>
        </div>
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'password'); ?>
            <?php echo $form->passwordField($model, 'password', array('size' => 60, 'maxlength' => 500, 'class' => 'form-control', 'readOnly' => TRUE)); ?>
            <?php echo $form->error($model, 'password'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'fullname'); ?>
            <?php echo $form->textField($model, 'fullname', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'fullname'); ?>
        </div>
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 500, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'phone'); ?>
            <?php echo $form->textField($model, 'phone', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'phone'); ?>
        </div>
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'genre'); ?>
            <?php echo $form->textField($model, 'genre', array('size' => 60, 'maxlength' => 500, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'genre'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'birthday'); ?>
            <?php echo $form->textField($model, 'birthday', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'birthday'); ?>
        </div>
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'address'); ?>
            <?php echo $form->textField($model, 'address', array('size' => 60, 'maxlength' => 500, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'address'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'description'); ?>
            <?php echo $form->textField($model, 'description', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'description'); ?>
        </div>
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'status'); ?>
            <?php echo $form->textField($model, 'status', array('size' => 60, 'maxlength' => 500, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'status'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'created_at'); ?>
            <?php echo $form->textField($model, 'created_at', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'created_at'); ?>
        </div>
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'updated_at'); ?>
            <?php echo $form->textField($model, 'updated_at', array('size' => 60, 'maxlength' => 500, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'updated_at'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'cp_id'); ?>
            <?php echo $form->textField($model, 'cp_id', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'cp_id'); ?>
        </div>
        <div class="col-md-6 col-xs-6" style="margin-top: 30px;">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->