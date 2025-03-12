<?php
/* @var $this ATransactionRequestController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Atransaction Requests',
);

$this->menu=array(
	array('label'=>'Create ATransactionRequest', 'url'=>array('create')),
	array('label'=>'Manage ATransactionRequest', 'url'=>array('admin')),
);
?>

<h1>Atransaction Requests</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
