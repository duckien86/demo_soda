<?php
/* @var $this AParentChildPackageCodesController */
/* @var $model AParentChildPackageCodes */

$this->breadcrumbs=array(
	'Aparent Child Package Codes'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>Yii::t('adm/actions', 'create'), 'url'=>array('create')),
);
?>
<br><br><br>

<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'aparent-child-package-codes-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
	'columns'=>array(
		'id',
		'parent_code',
		'child_code',
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'buttons'     => array(
				'view' => array(
					'options' => array('target' => '_blank'),
				),
			),
		),
	),
)); ?>
