<?php
    /* @var $this APartnerController */
    /* @var $model APartner */
    /* @var $form CActiveForm */
?>
<style>
    label {
        margin-top: 5px;
    }
</style>
<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'                   => 'apartner-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => FALSE,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <div class="form-inline pull-right">
            <div class="form-group">
                <div class="checkbox-nopad">
                    <label>
                        <?php
                            if ($model->isNewRecord) {
                                echo $form->checkBox($model, 'status', array('checked' => 'checked', 'class' => 'flat')) . ' ' . Yii::t('adm/app', 'Active');
                            } else {
                                echo $form->checkBox($model, 'status', array('class' => 'flat')) . ' ' . Yii::t('adm/app', 'Active');
                            }
                        ?>
                        &nbsp;&nbsp;&nbsp;</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'name'); ?>
            <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'name'); ?>
        </div>
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'description'); ?>
            <?php echo $form->textField($model, 'description', array('size' => 60, 'maxlength' => 500, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'description'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'cp_id'); ?>
            <?php echo $form->textField($model, 'cp_id', array('size' => 12, 'maxlength' => 12, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'cp_id'); ?>
        </div>

        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'return_url'); ?>
            <?php echo $form->textField($model, 'return_url', array('size' => 12, 'maxlength' => 1000, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'return_url'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'phone'); ?>
            <?php echo $form->textField($model, 'phone', array('size' => 12, 'maxlength' => 20, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'phone'); ?>
        </div>
        <div class="col-md-6 col-xs-6">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 500, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>
    </div>
    <?php if (!$model->isNewRecord): ?>
        <div class="row">
            <div class="col-md-6 col-xs-6">
                <?php echo $form->labelEx($model, 'aes_key'); ?>
                <?php echo $form->textField($model, 'aes_key', array('size' => 60, 'maxlength' => 500, 'class' => 'form-control', 'readOnly' => TRUE)); ?>
                <?php echo $form->error($model, 'aes_key'); ?>
            </div>
            <div class="col-md-6 col-xs-6">
                <?php echo $form->labelEx($model, 'created_at'); ?>
                <?php echo $form->textField($model, 'created_at', array('size' => 12, 'maxlength' => 12, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'created_at'); ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-6 col-xs-6" style="margin-top: 30px;">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
        </div>
        <div class="col-md-6 col-xs-6" style="margin-top: 30px;">

        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->