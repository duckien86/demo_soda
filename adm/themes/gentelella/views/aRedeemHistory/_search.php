<?php
/* @var $this ARedeemHistoryController */
/* @var $model ARedeemHistory */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sso_id'); ?>
		<?php echo $form->textField($model,'sso_id',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'package_code'); ?>
		<?php echo $form->textField($model,'package_code',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_date'); ?>
		<?php echo $form->textField($model,'create_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'expire_date'); ?>
		<?php echo $form->textField($model,'expire_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'point_amount'); ?>
		<?php echo $form->textField($model,'point_amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'transaction_id'); ?>
		<?php echo $form->textField($model,'transaction_id',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'msisdn'); ?>
		<?php echo $form->textField($model,'msisdn',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->