<?php
/* @var $this ABackendLogsController */
/* @var $model ABackendLogs */

$this->breadcrumbs=array(
	'Abackend Logs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ABackendLogs', 'url'=>array('index')),
	array('label'=>'Create ABackendLogs', 'url'=>array('create')),
	array('label'=>'View ABackendLogs', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ABackendLogs', 'url'=>array('admin')),
);
?>

<h1>Update ABackendLogs <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>