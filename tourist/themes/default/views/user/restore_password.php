<?php
/**
 * @var $this UserController
 * @var $model TRestorePassword
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
            <h4><b><?php echo CHtml::encode(Yii::t('tourist/label', 'restore_password'))?></b></h4>
        </div>
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id'                        => 'tuser-restorepassword-form',
            'method' => 'post',
//            'enableClientValidation'=>false,
            'enableAjaxValidation'=>true,
            'htmlOptions' => array(
//                'class' => 'form-horizontal text-left',
            ),
//            'clientOptions' => array(
//                'validateOnSubmit' => true,
//                'validateOnChange' => true,
//                'validateOnType' => false,
//            ),
//            'action' => Yii::app()->createUrl('user/forgetPassword'),
        )); ?>

        <div class="forgetpassword-error text-center">
            <h5 class="title-error"><?php echo $form->errorSummary($model); ?></h5>
        </div>

        <div class="form-group">
            <label style="font-size: 15px"><?php echo CHtml::encode(Yii::t('tourist/label','account')) . ': <span class="text-primary">' . $model->user->username .'</span>'?></label>
        </div>

        <div class="form-group">
            <?php echo $form->passwordField($model,'new_password',array(
                'placeholder' => Yii::t('tourist/label', 'enter_new_password'),
                'class' => 'form-control',
            ));
            ?>
        </div>
        <div class="form-group">
            <?php echo $form->passwordField($model,'re_new_password',array(
                'placeholder' => Yii::t('tourist/label', 'enter_re_new_password'),
                'class' => 'form-control',
            ));
            ?>
        </div>

        <div class="form-group">
            <?php echo CHtml::submitButton(Yii::t('tourist/label', 'change_password'), array(
                'id' => 'btn-restore_password',
                'class' => $btn_class,
            )); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>
