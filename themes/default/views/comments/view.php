<?php
/* @var $this CommentsController */
/* @var $model WComments */

$this->breadcrumbs=array(
	'Wcomments'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List WComments', 'url'=>array('index')),
	array('label'=>'Create WComments', 'url'=>array('create')),
	array('label'=>'Update WComments', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WComments', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WComments', 'url'=>array('admin')),
);
?>

<h1>View WComments #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'news_id',
		'ip',
		'comment_parent',
		'username',
		'email',
		'content',
		'status',
		'created_on',
	),
)); ?>
