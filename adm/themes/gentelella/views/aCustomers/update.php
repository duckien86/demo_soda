<?php
/* @var $this ACustomersController */
/* @var $model ACustomers */

$this->breadcrumbs=array(
	'Acustomers'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ACustomers', 'url'=>array('index')),
	array('label'=>'Create ACustomers', 'url'=>array('create')),
	array('label'=>'View ACustomers', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ACustomers', 'url'=>array('admin')),
);
?>

<h1>Update ACustomers <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>