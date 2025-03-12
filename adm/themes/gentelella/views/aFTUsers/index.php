<?php
/* @var $this AFTUsersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Aftusers',
);

$this->menu=array(
	array('label'=>'Create AFTUsers', 'url'=>array('create')),
	array('label'=>'Manage AFTUsers', 'url'=>array('admin')),
);
?>

<h1>Aftusers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
