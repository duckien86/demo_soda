<?php
/* @var $this AParentChildPackageCodesController */
/* @var $model AParentChildPackageCodes */

$this->breadcrumbs=array(
	'Aparent Child Package Codes'=>array('admin'),
	$model->id,
);

$this->menu=array(
	array('label'=>Yii::t('adm/actions', 'create'), 'url'=>array('create')),
	array('label'=>Yii::t('adm/actions', 'update'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('adm/actions', 'delete'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>Yii::t('adm/actions', 'manage'), 'url'=>array('admin')),
);
?>
<br><br><br>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'parent_code',
		'child_code',
	),
)); ?>
