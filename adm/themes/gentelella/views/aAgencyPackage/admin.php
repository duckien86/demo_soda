<?php
/* @var $this AgencyPackageController */
/* @var $model AgencyPackage */

$this->breadcrumbs=array(
	'Agency Packages'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List AgencyPackage', 'url'=>array('index')),
	array('label'=>'Create AgencyPackage', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#agency-package-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php echo CHtml::link(Yii::t('adm/label', 'create'), Yii::app()->createUrl('aAgencyPackage/create'), array('class' => 'btn btn-primary')) ?>
<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'agency-package-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
	'columns'=>array(
		'agency_id',
		'package_code',
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
