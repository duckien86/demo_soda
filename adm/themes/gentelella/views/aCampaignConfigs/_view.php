<?php
/* @var $this ACampaignConfigsController */
/* @var $data ACampaignConfigs */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('utm_source')); ?>:</b>
	<?php echo CHtml::encode($data->utm_source); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('utm_medium')); ?>:</b>
	<?php echo CHtml::encode($data->utm_medium); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('utm_campaign')); ?>:</b>
	<?php echo CHtml::encode($data->utm_campaign); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('target_link')); ?>:</b>
	<?php echo CHtml::encode($data->target_link); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_date')); ?>:</b>
	<?php echo CHtml::encode($data->create_date); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	*/ ?>

</div>