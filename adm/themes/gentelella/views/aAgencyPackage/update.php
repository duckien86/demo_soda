<?php
/* @var $this AgencyPackageController */
/* @var $model AgencyPackage */

$this->breadcrumbs=array(
	'Agency Packages'=>array('admin'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AgencyPackage', 'url'=>array('index')),
	array('label'=>'Create AgencyPackage', 'url'=>array('create')),
	array('label'=>'View AgencyPackage', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AgencyPackage', 'url'=>array('admin')),
);
?>
	
<?php $this->renderPartial('_form', array('model'=>$model)); ?>