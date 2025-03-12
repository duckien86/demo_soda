<?php
/* @var $this ARedeemHistoryController */
/* @var $model ARedeemHistory */

$this->breadcrumbs=array(
	'Aredeem Histories'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ARedeemHistory', 'url'=>array('index')),
	array('label'=>'Create ARedeemHistory', 'url'=>array('create')),
	array('label'=>'View ARedeemHistory', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ARedeemHistory', 'url'=>array('admin')),
);
?>

<h1>Update ARedeemHistory <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>