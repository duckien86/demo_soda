<?php
/* @var $this ABackendLogsController */
/* @var $model ABackendLogs */

$this->breadcrumbs=array(
	'Abackend Logs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ABackendLogs', 'url'=>array('index')),
	array('label'=>'Manage ABackendLogs', 'url'=>array('admin')),
);
?>

<h1>Create ABackendLogs</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>