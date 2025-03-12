<?php
/* @var $this AFTOrdersController */
/* @var $data AFTOrders */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('contract_id')); ?>:</b>
	<?php echo CHtml::encode($data->contract_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_update')); ?>:</b>
	<?php echo CHtml::encode($data->last_update); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('delivery_date')); ?>:</b>
	<?php echo CHtml::encode($data->delivery_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('finish_date')); ?>:</b>
	<?php echo CHtml::encode($data->finish_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('note')); ?>:</b>
	<?php echo CHtml::encode($data->note); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('ward_code')); ?>:</b>
	<?php echo CHtml::encode($data->ward_code); ?>
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('orderer_name')); ?>:</b>
	<?php echo CHtml::encode($data->orderer_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('orderer_phone')); ?>:</b>
	<?php echo CHtml::encode($data->orderer_phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('receiver_name')); ?>:</b>
	<?php echo CHtml::encode($data->receiver_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('accepted_payment_files')); ?>:</b>
	<?php echo CHtml::encode($data->accepted_payment_files); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total_success')); ?>:</b>
	<?php echo CHtml::encode($data->total_success); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total_fails')); ?>:</b>
	<?php echo CHtml::encode($data->total_fails); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('data_status')); ?>:</b>
	<?php echo CHtml::encode($data->data_status); ?>
	<br />

	*/ ?>

</div>