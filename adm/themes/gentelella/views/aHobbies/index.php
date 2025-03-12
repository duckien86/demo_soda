<?php
/* @var $this AHobbiesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ahobbies',
);

$this->menu=array(
	array('label'=>'Create AHobbies', 'url'=>array('create')),
	array('label'=>'Manage AHobbies', 'url'=>array('admin')),
);
?>

<h1>Ahobbies</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
