<?php
/* @var $this ACampaignConfigsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Acampaign Configs',
);

$this->menu=array(
	array('label'=>'Create ACampaignConfigs', 'url'=>array('create')),
	array('label'=>'Manage ACampaignConfigs', 'url'=>array('admin')),
);
?>

<h1>Acampaign Configs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
