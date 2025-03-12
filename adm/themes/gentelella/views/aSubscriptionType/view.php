<?php
/* @var $this ASubscriptionTypeController */
/* @var $model ASubscriptionType */

$this->breadcrumbs=array(
	'Asubscription Types'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List ASubscriptionType', 'url'=>array('index')),
	array('label'=>'Create ASubscriptionType', 'url'=>array('create')),
	array('label'=>'Update ASubscriptionType', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ASubscriptionType', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ASubscriptionType', 'url'=>array('admin')),
);
?>

<h1>View ASubscriptionType #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'type',
		'description',
	),
)); ?>
