<?php
    /* @var $this ALocationNapasController */
    /* @var $model ALocationNapas */
    /* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'alocationnapas-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => FALSE,
        'htmlOptions'          => array('enctype' => 'multipart/form-data')
    )); ?>

    <div class="form-group">
        <?= Yii::t('adm/actions', 'required_field') ?>
    </div>
    <?php echo $form->errorSummary($model); ?>

    <div class="col-md-5">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'province_code'); ?>
            <?php if($model->scenario == 'create'){
                $this->widget('booster.widgets.TbSelect2',
                    array(
                        'model'       => $model,
                        'attribute'   => 'province_code',
                        'data'        => CHtml::listData(AProvince::getAvailabilityProvinceForLocationNapas(),'code','name'),
                        'htmlOptions' => array(
                            'class'    => 'form-control form-item',
                            'multiple' => FALSE,
                            'prompt'   => Yii::t('adm/label', 'select'),
                        ),
                    )
                );
            }else{
                echo CHtml::link(CHtml::encode(AProvince::getProvinceNameByCode($model->id)),'javascript:void(0)', array(
                    'style' => 'display: block; font-size:15px; cursor: default; text-decoration: none; color: #666',
                ));
                echo $form->textField($model,'province_code',array('class' => 'hidden'));
            }
            ?>
            <?php echo $form->error($model, 'province_code'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'bank_account', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'bank_account', array('class' => 'textbox', 'size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'bank_account'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'bank_name', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'bank_name', array('class' => 'textbox', 'size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'bank_name'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'vpc_AccessCode', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'vpc_AccessCode', array(
                'class' => 'textbox',
                'size' => 60,
                'maxlength' => 255,
                'value' => ALocationNapas::$VPC_ACCESS_CODE,
            )); ?>
            <?php echo $form->error($model, 'vpc_AccessCode'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'vpc_Merchant', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'vpc_Merchant', array(
                'class' => 'textbox',
                'size' => 60,
                'maxlength' => 255,
                'value' => ALocationNapas::$VPC_MERCHANT,
            )); ?>
            <?php echo $form->error($model, 'vpc_Merchant'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'secure_secret', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'secure_secret', array(
                'class' => 'textbox',
                'size' => 60,
                'maxlength' => 255,
                'value' => ALocationNapas::$SECURE_SECRET,
            )); ?>
            <?php echo $form->error($model, 'secure_secret'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'end_point', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'end_point', array(
                'class' => 'textbox',
                'size' => 60,
                'maxlength' => 255,
                'value' => ALocationNapas::$END_POINT,
            )); ?>
            <?php echo $form->error($model, 'end_point'); ?>
        </div>

        <div class="form-group buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('adm/actions', 'create') : Yii::t('adm/actions', 'save'), array('class' => 'btn btn-success')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->