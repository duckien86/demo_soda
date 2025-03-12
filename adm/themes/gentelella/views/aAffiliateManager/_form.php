<?php
    /* @var $this AAffiliateManagerController */
    /* @var $model AAffiliateManager */
    /* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'aaffiliatemanager-form',
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
            <?php echo $form->labelEx($model, 'name', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'name', array(
                'class' => 'textbox',
                'maxlength' => 255,
            )); ?>
            <?php echo $form->error($model, 'name'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'code', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'code', array(
                'class' => 'textbox',
                'maxlength' => 255,
            )); ?>
            <?php echo $form->error($model, 'code'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'type', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->dropDownList($model, 'type',
                array(
                    AAffiliateManager::TYPE_LINK                => 'Mua hàng qua link (VD: Accesstrade)',
                    AAffiliateManager::TYPE_PARTNER_SITE        => 'Mua hàng trên trang đối tác (VD: mhtn)',
                    AAffiliateManager::TYPE_PARTNER_SITE_VNP    => 'Mua hàng trên trang đối tác VNP (VD: chonsovnp)',
                    AAffiliateManager::TYPE_AGENCY              => 'Đại lý tổ chức mua hàng trên backend: (VD: CellphoneS)',
                    AAffiliateManager::TYPE_FULL_API            => 'Mua hàng qua API (VD: zalo)'
                ),
                array(
                    'class' => 'form-control'
                )
            ); ?>
            <?php echo $form->error($model, 'type'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'url_redirect', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->textField($model, 'url_redirect', array(
                'class' => 'textbox',
                'maxlength' => 255,
            )); ?>
            <?php echo $form->error($model, 'url_redirect'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'status', array('class' => 'col-md-12 no_pad')); ?>
            <?php echo $form->dropDownList($model, 'status', AAffiliateManager::getListStatus(),array(
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