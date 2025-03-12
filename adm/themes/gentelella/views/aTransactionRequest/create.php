<?php
/* @var $this ATransactionRequestController */
/* @var $model ATransactionRequest */

$this->breadcrumbs=array(
	'Atransaction Requests'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ATransactionRequest', 'url'=>array('index')),
	array('label'=>'Manage ATransactionRequest', 'url'=>array('admin')),
);
?>

<h1>Create ATransactionRequest</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>