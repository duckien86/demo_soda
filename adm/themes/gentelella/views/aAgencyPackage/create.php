<?php
/* @var $this AgencyPackageController */
/* @var $model AgencyPackage */

$this->breadcrumbs=array(
	'Agency Packages'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AgencyPackage', 'url'=>array('index')),
	array('label'=>'Manage AgencyPackage', 'url'=>array('admin')),
);
?>


<?php $this->renderPartial('_form', array('model'=>$model)); ?>