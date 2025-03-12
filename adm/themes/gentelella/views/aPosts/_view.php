<?php
/* @var $this APostsController */
/* @var $data APosts */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('image')); ?>:</b>
	<?php echo CHtml::encode($data->image); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('media_url')); ?>:</b>
	<?php echo CHtml::encode($data->media_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total_comment')); ?>:</b>
	<?php echo CHtml::encode($data->total_comment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total_like')); ?>:</b>
	<?php echo CHtml::encode($data->total_like); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('sso_id')); ?>:</b>
	<?php echo CHtml::encode($data->sso_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('note')); ?>:</b>
	<?php echo CHtml::encode($data->note); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_date')); ?>:</b>
	<?php echo CHtml::encode($data->create_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_update')); ?>:</b>
	<?php echo CHtml::encode($data->last_update); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('post_category_id')); ?>:</b>
	<?php echo CHtml::encode($data->post_category_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('get_award')); ?>:</b>
	<?php echo CHtml::encode($data->get_award); ?>
	<br />

	*/ ?>

</div>