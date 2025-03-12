<?php
/* @var $this AgencyPackageController */
/* @var $data AgencyPackage */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('agency_id')); ?>:</b>
	<?php echo CHtml::encode($data->agency_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('package_code')); ?>:</b>
	<?php echo CHtml::encode($data->package_code); ?>
	<br />


</div>