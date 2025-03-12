<?php
/* @var $this ATransactionResponseController */
/* @var $model ATransactionResponse */

$this->breadcrumbs=array(
	'Atransaction Responses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ATransactionResponse', 'url'=>array('index')),
	array('label'=>'Manage ATransactionResponse', 'url'=>array('admin')),
);
?>

<h1>Create ATransactionResponse</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>