<?php
    /* @var $this ANewsCommentsController */
    /* @var $model ANewsComments */
    /* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'anewscomments-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
//        'enableAjaxValidation' => FALSE,
    )); ?>

    <div class="form-group">
        <?= Yii::t('adm/actions', 'required_field') ?>
    </div>
    <?php echo $form->errorSummary($model); ?>

    <div class="col-md-5">

        <div class="form-group">
            <?php echo $form->labelEx($model, 'content', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textArea($model, 'content', array(
                'class' => 'form-control'
            )); ?>
            <?php echo $form->error($model, 'status'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'status', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->dropDownList($model, 'status', ANewsComments::getListStatus(),array(
                'class' => 'dropdownlist',
                'maxlength' => 255,
            )); ?>
            <?php echo $form->error($model, 'status'); ?>
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