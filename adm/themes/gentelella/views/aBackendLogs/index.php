<?php
/* @var $this ABackendLogsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Abackend Logs',
);

$this->menu=array(
	array('label'=>'Create ABackendLogs', 'url'=>array('create')),
	array('label'=>'Manage ABackendLogs', 'url'=>array('admin')),
);
?>

<h1>Abackend Logs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
