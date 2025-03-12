<?php
/* @var $this ACustomersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Acustomers',
);

$this->menu=array(
	array('label'=>'Create ACustomers', 'url'=>array('create')),
	array('label'=>'Manage ACustomers', 'url'=>array('admin')),
);
?>

<h1>Acustomers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
