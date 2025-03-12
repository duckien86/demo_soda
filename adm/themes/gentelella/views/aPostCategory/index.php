<?php
/* @var $this APostCategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Apost Categories',
);

$this->menu=array(
	array('label'=>'Create APostCategory', 'url'=>array('create')),
	array('label'=>'Manage APostCategory', 'url'=>array('admin')),
);
?>

<h1>Apost Categories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
