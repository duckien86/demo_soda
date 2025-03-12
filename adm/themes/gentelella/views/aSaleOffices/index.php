<?php
/* @var $this ASaleOfficesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Asale Offices',
);

$this->menu=array(
	array('label'=>'Create ASaleOffices', 'url'=>array('create')),
	array('label'=>'Manage ASaleOffices', 'url'=>array('admin')),
);
?>

<h1>Asale Offices</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
