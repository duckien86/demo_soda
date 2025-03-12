<?php
/* @var $this AOrderWarningController */
/* @var $model AOrderWarning */

$this->breadcrumbs=array(
	'Aorder Warnings'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AOrderWarning', 'url'=>array('index')),
	array('label'=>'Create AOrderWarning', 'url'=>array('create')),
	array('label'=>'View AOrderWarning', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AOrderWarning', 'url'=>array('admin')),
);
?>

<h1>Update AOrderWarning <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>