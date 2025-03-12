<?php
    /* @var $this ALocationVietinbankController */
    /* @var $model ALocationVietinbank */
    /* @var $form CActiveForm */

    $prefix = '';
    $vnpProvinceId = '';
    if(Yii::app()->controller->action->id == 'update'){
        $vnpProvinceId = AProvince::getVnpProviceId($model->province_code);
        $vnpCode = $model->qr_code_merchant_id;
        $prefix = strstr($model->qr_code_merchant_id, $vnpProvinceId, true);
    }
?>

<div class="form">

    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'alocationvietinbank-form',
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
                        'data'        => CHtml::listData(AProvince::getAvailabilityProvinceForLocationVietinbank(),'code','name'),
                        'htmlOptions' => array(
                            'class'    => 'form-control form-item',
                            'multiple' => FALSE,
                            'prompt'   => Yii::t('adm/label', 'select'),
                            'onchange' => 'loadVNPCode()',
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
            <div class="prefix" style="display: inline-block; width: 50%; float: left">
                <?php echo $form->labelEx($model, 'prefix', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'prefix', array(
                    'class'         => 'textbox',
                    'size'          => 60,
                    'maxlength'     => 255,
                    'value'         => $prefix,
                )); ?>
                <?php echo $form->error($model, 'prefix'); ?>
            </div>
            <div class="qr_code_merchant_id" style="display: inline-block; width: 50%">
                <?php echo $form->labelEx($model, 'qr_code_merchant_id', array('class' => 'col-md-12 no_pad')); ?>
                <?php echo $form->textField($model, 'qr_code_merchant_id', array(
                    'class'     => 'textbox',
                    'size'      => 60, 'maxlength' => 255,
                    'readonly'  => 'readonly',
                    'style'     => 'background: #efefef',
                    'data-id'   => $vnpProvinceId,
                )); ?>
                <?php echo $form->error($model, 'qr_code_merchant_id'); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'access_key', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'access_key', array(
                'class' => 'textbox',
                'size' => 60,
                'maxlength' => 255,
                'value' => (!empty($model->access_key)) ? $model->access_key : ALocationVietinbank::$ACCESS_KEY,
            )); ?>
            <?php echo $form->error($model, 'access_key'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'profile_id', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'profile_id', array(
                'class' => 'textbox',
                'size' => 60,
                'maxlength' => 255,
                'value' => (!empty($model->profile_id)) ? $model->profile_id : ALocationVietinbank::$PROFILE_ID,
            )); ?>
            <?php echo $form->error($model, 'profile_id'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'secret_key', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textArea($model, 'secret_key', array(
                'class' => 'textarea',
                'size' => 60,
                'maxlength' => 255,
                'value' => (!empty($model->secret_key)) ? $model->secret_key : ALocationVietinbank::$SECRET_KEY,
            )); ?>
            <?php echo $form->error($model, 'secret_key'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'end_point', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'end_point', array(
                'class' => 'textbox',
                'size' => 60,
                'maxlength' => 255,
                'value' => (!empty($model->end_point)) ? $model->end_point : ALocationVietinbank::$END_POINT,
            )); ?>
            <?php echo $form->error($model, 'end_point'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'pServiceCode', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'pServiceCode', array(
                'class' => 'textbox',
                'size' => 60,
                'maxlength' => 255,
                'value' => (!empty($model->pServiceCode)) ? $model->pServiceCode : ALocationVietinbank::$P_SERVICE_CODE,
            )); ?>
            <?php echo $form->error($model, 'pServiceCode'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'pEnd_point', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'pEnd_point', array(
                'class' => 'textbox',
                'size' => 60,
                'maxlength' => 255,
                'value' => (!empty($model->pEnd_point)) ? $model->pEnd_point : ALocationVietinbank::$P_END_POINT,
            )); ?>
            <?php echo $form->error($model, 'pEnd_point'); ?>
        </div>

        <div class="form-group buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('adm/actions', 'create') : Yii::t('adm/actions', 'save'), array('class' => 'btn btn-success')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<script>

function loadVNPCode() {
    var form = $('#alocationvietinbank-form');
    $.ajax({
        url: '<?php echo Yii::app()->createUrl('aLocationVietinbank/loadvnpcode')?>',
        type: 'POST',
        dataType: 'json',
        data: form.serialize(),
        success: function (result) {
            $('#ALocationVietinbank_qr_code_merchant_id').attr('data-id', result);
            $('#ALocationVietinbank_qr_code_merchant_id').val(generateVnpCode());
        }
    });
}
function generateVnpCode() {
    var prefix = $('#ALocationVietinbank_prefix').val();
    var vnpProvinceId = $('#ALocationVietinbank_qr_code_merchant_id').attr('data-id');
    return prefix + vnpProvinceId;
}
$(document).ready(function () {
    $('#ALocationVietinbank_prefix').on('input', function () {
        $('#ALocationVietinbank_qr_code_merchant_id').val(generateVnpCode());
    });
});
</script>