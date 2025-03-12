<?php
/**
 * @var $this UserController
 * @var $model TLinkChangePass
 */

$this->pageTitle = 'Freedoo - ' . Yii::t('tourist/label', 'freedoo_tourist') . ' - ' . Yii::t('tourist/label', 'restore_password');
$btn_class = 'btn btn-lg';
if($this->isMobile) $btn_class = 'btn';
?>

<div id="forgetpassword" class="container text-center">
    <div class="modal-forgetpassword">
        <div class="form-title">
            <img src="<?php echo Yii::app()->theme->baseUrl . '/images/icon_login_title.png'?>"/>
        </div>
        <div class="form-title-description">
            <p><i><?php echo CHtml::encode(Yii::t('tourist/label', 'forgetpassword_note'))?></i></p>
        </div>
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id'                        => 'tuser-forgetpassword-form',
            'method' => 'post',
//            'enableClientValidation'=>false,
            'enableAjaxValidation'=>true,
            'focus'=>array($model,'username'),
            'htmlOptions' => array(
//                'class' => 'form-horizontal text-left',
            ),
//            'clientOptions' => array(
//                'validateOnSubmit' => true,
//                'validateOnChange' => true,
//                'validateOnType' => false,
//            ),
            'action' => Yii::app()->createUrl('user/forgetPassword'),
        )); ?>

        <div class="forgetpassword-error text-center">
            <h5 class="title-error"><?php echo $form->errorSummary($model); ?></h5>
        </div>

        <div class="form-group">
            <?php echo $form->textField($model,'username',array(
                'placeholder' => Yii::t('tourist/label', 'username'),
                'class' => 'form-control',
            ));
            ?>
        </div>

        <div class="form-group">
            <?php echo $form->emailField($model,'email',array(
                'placeholder' => Yii::t('tourist/label', 'email'),
                'class' => 'form-control',
            ));
            ?>
        </div>

        <div class="form-group">
            <?php echo CHtml::submitButton(Yii::t('tourist/label', 'authenticate'), array(
                'id' => 'btn-forgetpassword',
                'class' => $btn_class,
            )); ?>
            <a id="btn-back_to_login" href="<?php echo Yii::app()->createUrl('user/login');?>">
                <?php echo Yii::t('tourist/label', 'back_to_login'); ?>
            </a>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>
