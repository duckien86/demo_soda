<?php
/* @var $this AFTOrdersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Aftorders',
);

$this->menu=array(
	array('label'=>'Create AFTOrders', 'url'=>array('create')),
	array('label'=>'Manage AFTOrders', 'url'=>array('admin')),
);
?>

<h1>Aftorders</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
