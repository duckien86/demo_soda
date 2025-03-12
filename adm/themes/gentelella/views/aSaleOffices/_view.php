<?php
/* @var $this ASaleOfficesController */
/* @var $data ASaleOffices */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ward_code')); ?>:</b>
	<?php echo CHtml::encode($data->ward_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('district_code')); ?>:</b>
	<?php echo CHtml::encode($data->district_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('province_code')); ?>:</b>
	<?php echo CHtml::encode($data->province_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('location_type')); ?>:</b>
	<?php echo CHtml::encode($data->location_type); ?>
	<br />


</div>