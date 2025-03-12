<?php
/* @var $this CskhOrdersController */
/* @var $model CskhOrders */

$this->breadcrumbs=array(
	'Cskh Orders'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AOrders', 'url'=>array('index')),
	array('label'=>'Manage AOrders', 'url'=>array('admin')),
);
?>

<h1>Create AOrders</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>