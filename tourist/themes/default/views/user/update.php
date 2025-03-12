<?php
/**
 * @var $this UserController
 * @var $model TUsers
 */
?>

<div id="user">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'=>'tuser-form',
        'method' => 'post',
//        'enableAjaxValidation' => true,
//        'enableClientValidation' => true,
        'action'=> Yii::app()->controller->createUrl('user/update'),
        'htmlOptions' => array('enctype' => 'multipart/form-data', ),
    )); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'fullname', array('class' => 'form-title'))?>
        <?php echo $form->textField($model,'fullname', array('class' => 'form-control', 'maxlength' => 255))?>
        <?php echo $form->error($model,'fullname')?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'email', array('class' => 'form-title'))?>
        <?php echo $form->textField($model,'email', array('class' => 'form-control', 'maxlength' => 255))?>
        <?php echo $form->error($model,'email')?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'phone', array('class' => 'form-title'))?>
        <?php echo $form->textField($model,'phone', array('class' => 'form-control', 'maxlength' => 255))?>
        <?php echo $form->error($model,'phone')?>
    </div>


    <div class="form-group">
        <?php echo CHtml::submitButton(Yii::t('tourist/label', 'update_info'), array('id' => 'btn-submit', 'class' => 'btn btn-primary')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>
