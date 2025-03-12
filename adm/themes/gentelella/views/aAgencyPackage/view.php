<?php
/* @var $this AgencyPackageController */
/* @var $model AgencyPackage */

$this->breadcrumbs=array(
	'Agency Packages'=>array('admin'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List AgencyPackage', 'url'=>array('index')),
	array('label'=>'Create AgencyPackage', 'url'=>array('create')),
	array('label'=>'Update AgencyPackage', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete AgencyPackage', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AgencyPackage', 'url'=>array('admin')),
);
?>
<?php echo CHtml::link(Yii::t('adm/label', 'create'), Yii::app()->createUrl('aAgencyPackage/create'), array('class' => 'btn btn-primary')) ?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'agency_id',
		'package_code',
	),
)); ?>
