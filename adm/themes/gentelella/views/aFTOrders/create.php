<?php
/* @var $this AFTOrdersController */
/* @var $model AFTOrders */

$this->breadcrumbs=array(
	'Aftorders'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AFTOrders', 'url'=>array('index')),
	array('label'=>'Manage AFTOrders', 'url'=>array('admin')),
);
?>

<h1>Create AFTOrders</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>