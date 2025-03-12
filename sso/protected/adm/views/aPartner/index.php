<?php
/* @var $this APartnerController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Apartners',
);

$this->menu=array(
	array('label'=>'Create APartner', 'url'=>array('create')),
	array('label'=>'Manage APartner', 'url'=>array('admin')),
);
?>

<h1>Apartners</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
