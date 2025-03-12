<?php
/* @var $this AOrdersController */
/* @var $model AOrders */

$this->breadcrumbs=array(
	'Aorders'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AOrders', 'url'=>array('index')),
	array('label'=>'Create AOrders', 'url'=>array('create')),
	array('label'=>'View AOrders', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AOrders', 'url'=>array('admin')),
);
?>

<h1>Update AOrders <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>