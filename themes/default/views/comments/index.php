<?php
/* @var $this CommentsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Wcomments',
);

$this->menu=array(
	array('label'=>'Create WComments', 'url'=>array('create')),
	array('label'=>'Manage WComments', 'url'=>array('admin')),
);
?>

<h1>Wcomments</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
