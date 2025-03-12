<?php
/* @var $this ARedeemHistoryController */
/* @var $model ARedeemHistory */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'aredeem-history-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'sso_id'); ?>
		<?php echo $form->textField($model,'sso_id',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'sso_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'package_code'); ?>
		<?php echo $form->textField($model,'package_code',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'package_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_date'); ?>
		<?php echo $form->textField($model,'create_date'); ?>
		<?php echo $form->error($model,'create_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'expire_date'); ?>
		<?php echo $form->textField($model,'expire_date'); ?>
		<?php echo $form->error($model,'expire_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'point_amount'); ?>
		<?php echo $form->textField($model,'point_amount'); ?>
		<?php echo $form->error($model,'point_amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'transaction_id'); ?>
		<?php echo $form->textField($model,'transaction_id',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'transaction_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'msisdn'); ?>
		<?php echo $form->textField($model,'msisdn',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'msisdn'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->