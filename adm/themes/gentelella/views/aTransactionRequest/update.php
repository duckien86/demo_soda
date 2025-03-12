<?php
/* @var $this ATransactionRequestController */
/* @var $model ATransactionRequest */

$this->breadcrumbs=array(
	'Atransaction Requests'=>array('index'),
	$model->order_id=>array('view','id'=>$model->order_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ATransactionRequest', 'url'=>array('index')),
	array('label'=>'Create ATransactionRequest', 'url'=>array('create')),
	array('label'=>'View ATransactionRequest', 'url'=>array('view', 'id'=>$model->order_id)),
	array('label'=>'Manage ATransactionRequest', 'url'=>array('admin')),
);
?>

<h1>Update ATransactionRequest <?php echo $model->order_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>