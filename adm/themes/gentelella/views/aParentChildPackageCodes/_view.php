<?php
/* @var $this AParentChildPackageCodesController */
/* @var $data AParentChildPackageCodes */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parent_code')); ?>:</b>
	<?php echo CHtml::encode($data->parent_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('child_code')); ?>:</b>
	<?php echo CHtml::encode($data->child_code); ?>
	<br />


</div>