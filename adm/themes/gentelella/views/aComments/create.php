<?php
/* @var $this ACommentsController */
/* @var $model AComments */

$this->breadcrumbs=array(
	'Acomments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AComments', 'url'=>array('index')),
	array('label'=>'Manage AComments', 'url'=>array('admin')),
);
?>

<h1>Create AComments</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>