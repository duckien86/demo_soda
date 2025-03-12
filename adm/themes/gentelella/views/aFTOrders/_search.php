<?php
/* @var $this AFTOrdersController */
/* @var $model AFTOrders */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'contract_id'); ?>
		<?php echo $form->textField($model,'contract_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_time'); ?>
		<?php echo $form->textField($model,'create_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'last_update'); ?>
		<?php echo $form->textField($model,'last_update'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'delivery_date'); ?>
		<?php echo $form->textField($model,'delivery_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'finish_date'); ?>
		<?php echo $form->textField($model,'finish_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'note'); ?>
		<?php echo $form->textField($model,'note',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ward_code'); ?>
		<?php echo $form->textField($model,'ward_code',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'district_code'); ?>
		<?php echo $form->textField($model,'district_code',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'province_code'); ?>
		<?php echo $form->textField($model,'province_code',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'address_detail'); ?>
		<?php echo $form->textField($model,'address_detail',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'orderer_name'); ?>
		<?php echo $form->textField($model,'orderer_name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'orderer_phone'); ?>
		<?php echo $form->textField($model,'orderer_phone',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'receiver_name'); ?>
		<?php echo $form->textField($model,'receiver_name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'accepted_payment_files'); ?>
		<?php echo $form->textField($model,'accepted_payment_files'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'total_success'); ?>
		<?php echo $form->textField($model,'total_success',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'total_fails'); ?>
		<?php echo $form->textField($model,'total_fails',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'data_status'); ?>
		<?php echo $form->textField($model,'data_status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->