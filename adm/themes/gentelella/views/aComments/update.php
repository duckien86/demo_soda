<?php
/* @var $this ACommentsController */
/* @var $model AComments */

$this->breadcrumbs=array(
	'Acomments'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AComments', 'url'=>array('index')),
	array('label'=>'Create AComments', 'url'=>array('create')),
	array('label'=>'View AComments', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AComments', 'url'=>array('admin')),
);
?>

<h1>Update AComments <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>