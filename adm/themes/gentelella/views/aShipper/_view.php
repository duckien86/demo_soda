<?php
/* @var $this CskhShipperController */
/* @var $data CskhShipper */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('full_name')); ?>:</b>
	<?php echo CHtml::encode($data->full_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('avatar')); ?>:</b>
	<?php echo CHtml::encode($data->avatar); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone_1')); ?>:</b>
	<?php echo CHtml::encode($data->phone_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone_2')); ?>:</b>
	<?php echo CHtml::encode($data->phone_2); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('address_detail')); ?>:</b>
	<?php echo CHtml::encode($data->address_detail); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('district_code')); ?>:</b>
	<?php echo CHtml::encode($data->district_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('province_code')); ?>:</b>
	<?php echo CHtml::encode($data->province_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ward_code')); ?>:</b>
	<?php echo CHtml::encode($data->ward_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('brand_office_id')); ?>:</b>
	<?php echo CHtml::encode($data->brand_office_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('otp')); ?>:</b>
	<?php echo CHtml::encode($data->otp); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gender')); ?>:</b>
	<?php echo CHtml::encode($data->gender); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('birthday')); ?>:</b>
	<?php echo CHtml::encode($data->birthday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('personal_id')); ?>:</b>
	<?php echo CHtml::encode($data->personal_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('personal_id_create_date')); ?>:</b>
	<?php echo CHtml::encode($data->personal_id_create_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('personal_id_create_place')); ?>:</b>
	<?php echo CHtml::encode($data->personal_id_create_place); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	*/ ?>

</div>