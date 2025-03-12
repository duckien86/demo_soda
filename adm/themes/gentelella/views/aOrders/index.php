<?php
/* @var $this AOrdersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Aorders',
);

$this->menu=array(
	array('label'=>'Create AOrders', 'url'=>array('create')),
	array('label'=>'Manage AOrders', 'url'=>array('admin')),
);
?>

<h1>Aorders</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
