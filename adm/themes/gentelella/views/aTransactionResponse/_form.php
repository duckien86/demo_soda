<?php
/* @var $this ATransactionResponseController */
/* @var $model ATransactionResponse */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'atransaction-response-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'order_id'); ?>
		<?php echo $form->textField($model,'order_id',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'order_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'partner'); ?>
		<?php echo $form->textField($model,'partner',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'partner'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'payment_method'); ?>
		<?php echo $form->textField($model,'payment_method',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'payment_method'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'transaction_id'); ?>
		<?php echo $form->textField($model,'transaction_id',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'transaction_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'request'); ?>
		<?php echo $form->textArea($model,'request',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'request'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'response'); ?>
		<?php echo $form->textArea($model,'response',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'response'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_date'); ?>
		<?php echo $form->textField($model,'create_date'); ?>
		<?php echo $form->error($model,'create_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'note'); ?>
		<?php echo $form->textArea($model,'note',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'note'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'request_data_type'); ?>
		<?php echo $form->textField($model,'request_data_type',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'request_data_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'response_data_type'); ?>
		<?php echo $form->textField($model,'response_data_type',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'response_data_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'endpoint'); ?>
		<?php echo $form->textArea($model,'endpoint',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'endpoint'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->