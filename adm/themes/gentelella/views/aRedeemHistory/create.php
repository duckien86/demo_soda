<?php
/* @var $this ARedeemHistoryController */
/* @var $model ARedeemHistory */

$this->breadcrumbs=array(
	'Aredeem Histories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ARedeemHistory', 'url'=>array('index')),
	array('label'=>'Manage ARedeemHistory', 'url'=>array('admin')),
);
?>

<h1>Create ARedeemHistory</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>