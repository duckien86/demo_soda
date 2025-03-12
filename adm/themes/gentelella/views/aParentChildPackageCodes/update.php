<?php
/* @var $this AParentChildPackageCodesController */
/* @var $model AParentChildPackageCodes */

$this->breadcrumbs=array(
	'Aparent Child Package Codes'=>array('admin'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>Yii::t('adm/actions', 'manage'), 'url'=>array('admin')),
);
?>
	<br><br><br>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>