<?php
/* @var $this ACustomersController */
/* @var $model ACustomers */

$this->breadcrumbs=array(
	'Acustomers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ACustomers', 'url'=>array('index')),
	array('label'=>'Manage ACustomers', 'url'=>array('admin')),
);
?>

<h1>Create ACustomers</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>