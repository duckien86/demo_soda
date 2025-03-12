<?php
/* @var $this AQuestionAnswerController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Aquestion Answers',
);

$this->menu=array(
	array('label'=>'Create AQuestionAnswer', 'url'=>array('create')),
	array('label'=>'Manage AQuestionAnswer', 'url'=>array('admin')),
);
?>

<h1>Aquestion Answers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
