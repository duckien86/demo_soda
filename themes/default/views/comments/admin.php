<?php
/* @var $this CommentsController */
/* @var $model WComments */

$this->breadcrumbs=array(
	'Wcomments'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List WComments', 'url'=>array('index')),
	array('label'=>'Create WComments', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#wcomments-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Wcomments</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'wcomments-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'news_id',
		'ip',
		'comment_parent',
		'username',
		'email',
		/*
		'content',
		'status',
		'created_on',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
