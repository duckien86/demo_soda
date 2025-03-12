<?php
    /* @var $this ALocationVnptpayController */
    /* @var $model ALocationVnptpay */
    /* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'alocationvnptpay-form',
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
                        'data'        => CHtml::listData(AProvince::getAvailabilityProvinceForLocationVNPTPay(),'code','name'),
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
            <?php echo $form->labelEx($model, 'merchant_service_id', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'merchant_service_id', array(
                'class' => 'textbox',
                'size' => 60,
                'maxlength' => 255,
                'value' => ALocationVnptpay::$MERCHANT_SERVICE_ID,
            )); ?>
            <?php echo $form->error($model, 'merchant_service_id'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'service_id', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'service_id', array(
                'class' => 'textbox',
                'size' => 60,
                'maxlength' => 255,
                'value' => ALocationVnptpay::$SERVICE_ID,
            )); ?>
            <?php echo $form->error($model, 'service_id'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'agency_id', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'agency_id', array(
                'class' => 'textbox',
                'size' => 60,
                'maxlength' => 255,
                'value' => ALocationVnptpay::$AGENCY_ID,
            )); ?>
            <?php echo $form->error($model, 'agency_id'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'secret_key', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'secret_key', array(
                'class' => 'textbox',
                'size' => 60,
                'maxlength' => 255,
                'value' => ALocationVnptpay::$SECRET_KEY,
            )); ?>
            <?php echo $form->error($model, 'secret_key'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'end_point', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'end_point', array(
                'class' => 'textbox',
                'size' => 60,
                'maxlength' => 255,
                'value' => ALocationVnptpay::$END_POINT,
            )); ?>
            <?php echo $form->error($model, 'end_point'); ?>
        </div>

        <div class="form-group buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('adm/actions', 'create') : Yii::t('adm/actions', 'save'), array('class' => 'btn btn-success')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->