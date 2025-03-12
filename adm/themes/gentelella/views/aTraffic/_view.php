<?php
/* @var $this AOrdersController */
/* @var $data AOrders */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sso_id')); ?>:</b>
	<?php echo CHtml::encode($data->sso_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('promo_code')); ?>:</b>
	<?php echo CHtml::encode($data->promo_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_date')); ?>:</b>
	<?php echo CHtml::encode($data->create_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_update')); ?>:</b>
	<?php echo CHtml::encode($data->last_update); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shipper_id')); ?>:</b>
	<?php echo CHtml::encode($data->shipper_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('delivery_type')); ?>:</b>
	<?php echo CHtml::encode($data->delivery_type); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('payment_method')); ?>:</b>
	<?php echo CHtml::encode($data->payment_method); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('personal_id')); ?>:</b>
	<?php echo CHtml::encode($data->personal_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('full_name')); ?>:</b>
	<?php echo CHtml::encode($data->full_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('birthday')); ?>:</b>
	<?php echo CHtml::encode($data->birthday); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('district')); ?>:</b>
	<?php echo CHtml::encode($data->district); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('province')); ?>:</b>
	<?php echo CHtml::encode($data->province); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address_detail')); ?>:</b>
	<?php echo CHtml::encode($data->address_detail); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone_contact')); ?>:</b>
	<?php echo CHtml::encode($data->phone_contact); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_note')); ?>:</b>
	<?php echo CHtml::encode($data->customer_note); ?>
	<br />

	*/ ?>

</div>