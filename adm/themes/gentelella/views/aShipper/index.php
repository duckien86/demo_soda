<?php
/* @var $this CskhShipperController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Cskh Shippers',
);

$this->menu=array(
	array('label'=>'Create CskhShipper', 'url'=>array('create')),
	array('label'=>'Manage CskhShipper', 'url'=>array('admin')),
);
?>

<h1>Cskh Shippers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
