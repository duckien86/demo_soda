<?php
/* @var $this CommentsController */
/* @var $model WComments */

$this->breadcrumbs=array(
	'Wcomments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List WComments', 'url'=>array('index')),
	array('label'=>'Manage WComments', 'url'=>array('admin')),
);
?>

<h1>Create WComments</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>