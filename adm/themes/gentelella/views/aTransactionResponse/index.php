<?php
/* @var $this ATransactionResponseController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Atransaction Responses',
);

$this->menu=array(
	array('label'=>'Create ATransactionResponse', 'url'=>array('create')),
	array('label'=>'Manage ATransactionResponse', 'url'=>array('admin')),
);
?>

<h1>Atransaction Responses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
