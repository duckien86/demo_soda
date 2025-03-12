<?php
/* @var $this ACategoryQaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Acategory Qas',
);

$this->menu=array(
	array('label'=>'Create ACategoryQa', 'url'=>array('create')),
	array('label'=>'Manage ACategoryQa', 'url'=>array('admin')),
);
?>

<h1>Acategory Qas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
