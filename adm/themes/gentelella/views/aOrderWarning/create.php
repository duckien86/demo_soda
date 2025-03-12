<?php
/* @var $this AOrderWarningController */
/* @var $model AOrderWarning */

$this->breadcrumbs=array(
	'Aorder Warnings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AOrderWarning', 'url'=>array('index')),
	array('label'=>'Manage AOrderWarning', 'url'=>array('admin')),
);
?>

<h1>Create AOrderWarning</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>