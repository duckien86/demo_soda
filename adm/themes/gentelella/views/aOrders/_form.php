<?php
/* @var $this AOrdersController */
/* @var $model AOrders */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'aorders-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sso_id'); ?>
		<?php echo $form->textField($model,'sso_id',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'sso_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'promo_code'); ?>
		<?php echo $form->textField($model,'promo_code',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'promo_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_date'); ?>
		<?php echo $form->textField($model,'create_date'); ?>
		<?php echo $form->error($model,'create_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_update'); ?>
		<?php echo $form->textField($model,'last_update'); ?>
		<?php echo $form->error($model,'last_update'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'shipper_id'); ?>
		<?php echo $form->textField($model,'shipper_id',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'shipper_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'delivery_type'); ?>
		<?php echo $form->textField($model,'delivery_type',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'delivery_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'payment_method'); ?>
		<?php echo $form->textField($model,'payment_method',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'payment_method'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'personal_id'); ?>
		<?php echo $form->textField($model,'personal_id',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'personal_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'full_name'); ?>
		<?php echo $form->textField($model,'full_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'full_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'birthday'); ?>
		<?php echo $form->textField($model,'birthday'); ?>
		<?php echo $form->error($model,'birthday'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'district'); ?>
		<?php echo $form->textField($model,'district',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'district'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'province'); ?>
		<?php echo $form->textField($model,'province',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'province'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address_detail'); ?>
		<?php echo $form->textField($model,'address_detail',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address_detail'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone_contact'); ?>
		<?php echo $form->textField($model,'phone_contact',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'phone_contact'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'customer_note'); ?>
		<?php echo $form->textField($model,'customer_note',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'customer_note'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->