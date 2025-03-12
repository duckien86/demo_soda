<?php
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'          => 'inlineForm',
        'method'      => 'post',
        'type'        => 'inline',
        'htmlOptions' => array('class' => 'well'),
    ));

    echo CHtml::hiddenField('post', 1);
?>

<div class="row">
    <label>Query String</label>
</div>
<div class="row">
    <?php echo CHtml::textArea('query_string', Yii::app()->session['query_string'], array('class' => 'form-control', 'style' => 'width:800px;height:120px;', 'placeholder' => 'Enter Query String here')); ?>
</div>
<div class="clear" style="margin-bottom: 10px;"></div>
<div class="col-md-3">
    <div class="row">
        <label>DTB Type</label>
    </div>
    <div class="row">
        <?php echo CHtml::radioButtonList('db_type', $db_type, array('mysql' => 'My sql')); ?>
    </div>
</div>
<div class="col-md-3">
    <div class="row">
        <label>Hiển thị</label>
    </div>
    <div class="row">
        <?php echo CHtml::radioButtonList('option', $option, array('1' => 'Thực thi & hiển thị kết quả truy vấn', '0' => 'Chỉ thực thi câu lệnh')); ?>
    </div>
</div>
<div class="col-md-3">
    <div class="row">
        <label>Limit</label>
    </div>
    <div class="row">
        <?php echo CHtml::radioButtonList('oplimit', $op_limit, array('40' => '40', '100' => '100', 'unlimit' => 'Un limit')); ?>
    </div>
</div>
<div class="clear"></div>
<div class="row">
    <?php
        $this->widget('booster.widgets.TbButton', array('buttonType' => 'submit', 'context' => 'primary', 'label' => 'Submit'));
        $this->endWidget(); ?>
</div>
<div class="row">
    <?php
        if ($dataProvider) {
            $this->widget('booster.widgets.TbGridView', array(
                    'type'         => 'striped',
                    'dataProvider' => $dataProvider,
                    'summaryText'  => FALSE,
                    'htmlOptions'  => array(),
                )
            );
        }
    ?>
</div>