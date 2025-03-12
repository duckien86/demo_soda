<?php
/**
 * @var $this AFTUsersController
 * @var $model AFTUsers
 * @var $form CActiveForm
 */

$class_account = ($model->user_type == AFTUsers::USER_TYPE_AGENCY) ? 'hidden' : '';
$class_receive = ($model->user_type == AFTUsers::USER_TYPE_AGENCY) ? '' : 'hidden';
?>

<div class="form">

    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'aftusers-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => TRUE,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>


    <?php if ($model->isNewRecord) { ?>
    <div class="row">
        <div id="account_control"  class="col-md-6 <?php echo $class_account?>">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'username'); ?>
                <?php echo $form->textField($model, 'username', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'username'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'password'); ?>
                <?php echo $form->passwordField($model, 'password', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'password'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 're_password'); ?>
                <?php echo $form->passwordField($model, 're_password', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 're_password'); ?>
            </div>
        </div>

        <div class="col-md-6">

            <div class="form-group">
                <?php echo $form->labelEx($model, 'user_type'); ?>
                <?php echo $form->dropDownList($model, 'user_type', AFTUsers::getListActiveType(), array(
                    'class' => 'form-control',
                    'onchange' => 'checkUserType($(this).val())',
                ));?>
                <?php echo $form->error($model, 'user_type'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'company'); ?>
                <?php echo $form->textField($model, 'company', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'company'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'sale_code'); ?>
                <?php echo $form->textField($model, 'sale_code', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'sale_code'); ?>
            </div>
        </div>
    </div>

    <hr width="80%"/>
    <?php } ?>


    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'email'); ?>
                <?php echo $form->textField($model, 'email', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'email'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'phone'); ?>
                <?php echo $form->textField($model, 'phone', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'phone'); ?>
            </div>

        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'fullname'); ?>
                <?php echo $form->textField($model, 'fullname', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'fullname'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'address'); ?>
                <?php echo $form->textField($model, 'address', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'address'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'tax_id'); ?>
                <?php echo $form->textField($model, 'tax_id', array('class' => 'textbox', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'tax_id'); ?>
            </div>

        </div>
    </div>


    <div id="receive_control" class="<?php echo $class_receive?>">
        <hr width="80%"/>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'receive_method');?>
                    <?php echo $form->dropDownList($model, 'receive_method', AFTUsers::getListReceiveMethod(), array('class' => 'form-control'))?>
                    <?php echo $form->error($model, 'receive_method');?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'receive_endpoint');?>
                    <?php echo $form->textField($model, 'receive_endpoint', array('class' => 'form-control'))?>
                    <?php echo $form->error($model, 'receive_method');?>
                </div>
            </div>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-md-6 col-xs-12">
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('adm/actions', 'create') : Yii::t('adm/actions', 'save'), array('class' => 'btn btn-success')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<script>
function checkUserType(type){
    if(type == <?php echo AFTUsers::USER_TYPE_AGENCY?>){
        $('#receive_control').removeClass('hidden');
        $('#account_control').addClass('hidden');
    }else{
        $('#receive_control').addClass('hidden');
        $('#account_control').removeClass('hidden');
    }
}
</script>
