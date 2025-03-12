<?php
/* @var $this APostsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Aposts',
);

$this->menu=array(
	array('label'=>'Create APosts', 'url'=>array('create')),
	array('label'=>'Manage APosts', 'url'=>array('admin')),
);
?>

<h1>Aposts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
