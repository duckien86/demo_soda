<?php
/* @var $this ACommentsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Acomments',
);

$this->menu=array(
	array('label'=>'Create AComments', 'url'=>array('create')),
	array('label'=>'Manage AComments', 'url'=>array('admin')),
);
?>

<h1>Acomments</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
