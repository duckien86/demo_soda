<?php
/* @var $this AOrderWarningController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Aorder Warnings',
);

$this->menu=array(
	array('label'=>'Create AOrderWarning', 'url'=>array('create')),
	array('label'=>'Manage AOrderWarning', 'url'=>array('admin')),
);
?>

<h1>Aorder Warnings</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
