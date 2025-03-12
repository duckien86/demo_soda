<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'export-data',
    'method' => 'POST',
    'action' => Yii::app()->createUrl('queryTool/exportData'),
	'htmlOptions' => array(
		'target' => '_blank'
	)
)); ?>
	<div class="buttons">
		<?php echo CHtml::submitButton('Xuất dữ liệu', array('class' =>'btn btn-warning')); ?>
	</div>

<?php $this->endWidget(); ?>