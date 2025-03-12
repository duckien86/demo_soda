<?php
/* @var $this ATransactionResponseController */
/* @var $model ATransactionResponse */

$this->breadcrumbs=array(
	'Atransaction Responses'=>array('index'),
	$model->order_id=>array('view','id'=>$model->order_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ATransactionResponse', 'url'=>array('index')),
	array('label'=>'Create ATransactionResponse', 'url'=>array('create')),
	array('label'=>'View ATransactionResponse', 'url'=>array('view', 'id'=>$model->order_id)),
	array('label'=>'Manage ATransactionResponse', 'url'=>array('admin')),
);
?>

<h1>Update ATransactionResponse <?php echo $model->order_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>