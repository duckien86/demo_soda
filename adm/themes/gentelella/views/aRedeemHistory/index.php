<?php
/* @var $this ARedeemHistoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Aredeem Histories',
);

$this->menu=array(
	array('label'=>'Create ARedeemHistory', 'url'=>array('create')),
	array('label'=>'Manage ARedeemHistory', 'url'=>array('admin')),
);
?>

<h1>Aredeem Histories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
