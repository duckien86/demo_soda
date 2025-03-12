<?php
/* @var $this ACustomersController */
/* @var $data ACustomers */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sso_id')); ?>:</b>
	<?php echo CHtml::encode($data->sso_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</b>
	<?php echo CHtml::encode($data->phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('birthday')); ?>:</b>
	<?php echo CHtml::encode($data->birthday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bonus_point')); ?>:</b>
	<?php echo CHtml::encode($data->bonus_point); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_update')); ?>:</b>
	<?php echo CHtml::encode($data->last_update); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('otp')); ?>:</b>
	<?php echo CHtml::encode($data->otp); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('full_name')); ?>:</b>
	<?php echo CHtml::encode($data->full_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('genre')); ?>:</b>
	<?php echo CHtml::encode($data->genre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_type')); ?>:</b>
	<?php echo CHtml::encode($data->customer_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('district_code')); ?>:</b>
	<?php echo CHtml::encode($data->district_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('province_code')); ?>:</b>
	<?php echo CHtml::encode($data->province_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address_detail')); ?>:</b>
	<?php echo CHtml::encode($data->address_detail); ?>
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('extra_info')); ?>:</b>
	<?php echo CHtml::encode($data->extra_info); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_account_id')); ?>:</b>
	<?php echo CHtml::encode($data->bank_account_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_brandname')); ?>:</b>
	<?php echo CHtml::encode($data->bank_brandname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_name')); ?>:</b>
	<?php echo CHtml::encode($data->bank_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_account_name')); ?>:</b>
	<?php echo CHtml::encode($data->bank_account_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('job')); ?>:</b>
	<?php echo CHtml::encode($data->job); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nation')); ?>:</b>
	<?php echo CHtml::encode($data->nation); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('avatar')); ?>:</b>
	<?php echo CHtml::encode($data->avatar); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('profile_picture')); ?>:</b>
	<?php echo CHtml::encode($data->profile_picture); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('level')); ?>:</b>
	<?php echo CHtml::encode($data->level); ?>
	<br />

	*/ ?>

</div>