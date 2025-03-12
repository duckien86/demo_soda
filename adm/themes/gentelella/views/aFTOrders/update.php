<?php
/* @var $this AFTOrdersController */
/* @var $model AFTOrders */

$this->breadcrumbs=array(
	'Aftorders'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AFTOrders', 'url'=>array('index')),
	array('label'=>'Create AFTOrders', 'url'=>array('create')),
	array('label'=>'View AFTOrders', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AFTOrders', 'url'=>array('admin')),
);
?>

<h1>Update AFTOrders <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>