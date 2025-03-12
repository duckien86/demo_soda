<?php
/**
 * @var $this UserController
 * @var $model TChangePasswordForm
 */
$this->pageTitle = 'Freedoo - ' . Yii::t('tourist/label', 'freedoo_tourist') . ' - ' . Yii::t('tourist/label', 'change_password');
?>

<div id="user">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'=>'tuser-changepassword-form',
        'method' => 'post',
//        'enableAjaxValidation' => true,
//        'enableClientValidation' => true,
        'action'=> Yii::app()->controller->createUrl('user/changePassword'),
        'htmlOptions' => array('enctype' => 'multipart/form-data', ),
    )); ?>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'password', array('class' => 'form-title'));?>
        <?php echo $form->passwordField($model, 'password', array(
            'class' => 'form-control',
            'maxlength' => 32
        ));?>
        <?php echo $form->error($model, 'password'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'new_password', array('class' => 'form-title'));?>
        <?php echo $form->passwordField($model, 'new_password', array(
            'class' => 'form-control',
            'maxlength' => 32
        ));?>
        <?php echo $form->error($model, 'new_password'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 're_new_password', array('class' => 'form-title'));?>
        <?php echo $form->passwordField($model, 're_new_password', array(
            'class' => 'form-control',
            'maxlength' => 32
        ));?>
        <?php echo $form->error($model, 're_new_password'); ?>
    </div>
<!--    <div class="form-group">-->
<!--        <label>-->
<!--            --><?php //echo $form->checkBox($model, 'logout', array('class' => 'flat')) . ' ' . Yii::t('tourist/label', 'logout_after_change_password'); ?>
<!--        </label>-->
<!--    </div>-->

    <div class="form-group">
        <?php echo CHtml::submitButton(Yii::t('tourist/label', 'change_password'), array('id' => 'btn-submit', 'class' => 'btn btn-primary')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>
