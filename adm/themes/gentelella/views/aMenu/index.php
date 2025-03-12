<?php
/* @var $this AMenuController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Amenus',
);

$this->menu=array(
	array('label'=>'Create AMenu', 'url'=>array('create')),
	array('label'=>'Manage AMenu', 'url'=>array('admin')),
);
?>

<h1>Amenus</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
