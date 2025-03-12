<?php
/**
 * @var UserController $this
 * @var TLoginForm $model
 */
$this->pageTitle = 'Freedoo - ' . Yii::t('tourist/label', 'freedoo_tourist') . ' - ' . Yii::t('tourist/label', 'login');
$btn_class = 'btn btn-lg';
if($this->isMobile) $btn_class = 'btn';
?>

<div id="login" class="container text-center">
    <div class="modal-login">
        <div class="form-title">
            <img src="<?php echo Yii::app()->theme->baseUrl . '/images/icon_login_title.png'?>"/>
        </div>
        <div class="form-title-description">
            <p><i><?php echo CHtml::encode(Yii::t('tourist/label', 'login_note_3'))?></i></p>
            <h4><b><?php echo CHtml::encode(Yii::t('tourist/label', 'login_note_2'))?></b></h4>
        </div>
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id'                        => 'tuser-login-form',
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
            'action' => Yii::app()->createUrl('user/loginCtv'),
        )); ?>

        <div class="login-error text-center">
            <h5 class="title-error"><?php echo $form->errorSummary($model); ?></h5>
        </div>

        <div class="form-group">
            <?php echo $form->textField($model,'username',array(
                'maxlength'=>32,
                'placeholder' => Yii::t('tourist/label', 'username'),
                'class' => 'form-control',
            ));
            ?>
        </div>

        <div class="form-group">
            <?php echo $form->passwordField($model,'password',array(
                'maxlength'=>32,
                'placeholder' => Yii::t('tourist/label', 'password'),
                'class' => 'form-control',
            ));
            ?>
        </div>

        <div class="form-group">
            <?php echo CHtml::submitButton(Yii::t('tourist/label', 'login'), array(
                'id' => 'btn-login',
                'class' => $btn_class,
            )); ?>
        </div>
        
        <?php $this->endWidget(); ?>
    </div>
</div>