<?php
/* @var $this AFTOrdersController */
/* @var $model AFTOrders */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'aftorders-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'contract_id'); ?>
		<?php echo $form->textField($model,'contract_id'); ?>
		<?php echo $form->error($model,'contract_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_time'); ?>
		<?php echo $form->textField($model,'create_time'); ?>
		<?php echo $form->error($model,'create_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_update'); ?>
		<?php echo $form->textField($model,'last_update'); ?>
		<?php echo $form->error($model,'last_update'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'delivery_date'); ?>
		<?php echo $form->textField($model,'delivery_date'); ?>
		<?php echo $form->error($model,'delivery_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'finish_date'); ?>
		<?php echo $form->textField($model,'finish_date'); ?>
		<?php echo $form->error($model,'finish_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'note'); ?>
		<?php echo $form->textField($model,'note',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'note'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ward_code'); ?>
		<?php echo $form->textField($model,'ward_code',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'ward_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'district_code'); ?>
		<?php echo $form->textField($model,'district_code',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'district_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'province_code'); ?>
		<?php echo $form->textField($model,'province_code',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'province_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address_detail'); ?>
		<?php echo $form->textField($model,'address_detail',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address_detail'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'orderer_name'); ?>
		<?php echo $form->textField($model,'orderer_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'orderer_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'orderer_phone'); ?>
		<?php echo $form->textField($model,'orderer_phone',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'orderer_phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'receiver_name'); ?>
		<?php echo $form->textField($model,'receiver_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'receiver_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'accepted_payment_files'); ?>
		<?php echo $form->textField($model,'accepted_payment_files'); ?>
		<?php echo $form->error($model,'accepted_payment_files'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'total_success'); ?>
		<?php echo $form->textField($model,'total_success',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'total_success'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'total_fails'); ?>
		<?php echo $form->textField($model,'total_fails',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'total_fails'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'data_status'); ?>
		<?php echo $form->textField($model,'data_status'); ?>
		<?php echo $form->error($model,'data_status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->