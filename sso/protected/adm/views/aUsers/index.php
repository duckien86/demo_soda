<?php
/* @var $this AUsersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ausers',
);

$this->menu=array(
	array('label'=>'Create AUsers', 'url'=>array('create')),
	array('label'=>'Manage AUsers', 'url'=>array('admin')),
);
?>

<h1>Ausers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
