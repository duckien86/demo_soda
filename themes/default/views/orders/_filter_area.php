<?php
    /* @var $this OrdersController */
    /* @var $modelSearch SearchOrderForm */
    /* @var $form CActiveForm */
?>
<div class="fillterarea form">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'filter_order',
        'enableAjaxValidation' => TRUE,
    )); ?>
    <div class="form-group">
        <div class="item">
            <?php echo $form->label($modelSearch, 'id'); ?>
            <?php echo $form->textField($modelSearch, 'id', array('class' => 'textbox', 'maxlength' => 255)); ?>
            <?php echo $form->error($modelSearch, 'id'); ?>
        </div>
        <div class="item">
            <?php echo $form->label($modelSearch, 'phone_contact'); ?>
            <?php echo $form->textField($modelSearch, 'phone_contact', array('class' => 'textbox', 'maxlength' => 255)); ?>
            <?php echo $form->error($modelSearch, 'phone_contact'); ?>
        </div>
        <div class="item">
            <?php echo CHtml::submitButton(Yii::t('web/portal', 'search'),
                array('class' => 'btn btn_green')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div>
<div class="space_10"></div>
