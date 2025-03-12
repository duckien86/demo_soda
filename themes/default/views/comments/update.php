<?php
/* @var $this CommentsController */
/* @var $model WComments */

$this->breadcrumbs=array(
	'Wcomments'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List WComments', 'url'=>array('index')),
	array('label'=>'Create WComments', 'url'=>array('create')),
	array('label'=>'View WComments', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage WComments', 'url'=>array('admin')),
);
?>

<h1>Update WComments <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>