<?php
    /* @var $this RoamingController */
    /* @var $otpModel OtpForm */
    /* @var $form CActiveForm */
    /* @var $msg */
?>
<script src='https://www.google.com/recaptcha/api.js?hl=vi'></script>
<div class="text-center font_15 lbl_color_pink">
    Quý khách vui lòng nhập SĐT để đăng ký CVQT
</div>
<div class="space_20"></div>
<?php Yii::app()->getClientScript()->registerCoreScript('jquery'); ?>
<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id'                   => 'form_send_otp_ir_only',
    'action'               => Yii::app()->controller->createUrl('roaming/SendOtpIrOnly'),
    'enableAjaxValidation' => TRUE,
    'enableClientValidation' => FALSE,
    'htmlOptions'          => array('onsubmit' => 'return false;'),
)); ?>
<div class="col-md-2"></div>
<div class="col-md-8">
    <div class="form-group">
        <?php
            echo $form->textField($otpModel, 'msisdn', array(
                'class'       => 'textbox_lg',
                'maxlength'   => 255,
                'placeholder' => Yii::t('web/portal', 'input_otp_msisdn'),
                'onchange'  => 'changeMsisdnPrefix(this, null);',
            ));
        ?>
        <?php echo $form->error($otpModel, 'msisdn'); ?>
    </div>
    <div class="form-group">
        <div id="captcha_place_holder"
             class="g-recaptcha"
             data-sitekey="6LdnWS4UAAAAAAyy0Odc6bAuWs8wEm6BD9A6h66t"></div>
        <?php echo $form->error($otpModel, 'captcha'); ?>
    </div>
</div>
<div class="col-md-2"></div>
<div class="space_10"></div>
<div class="package_info text-center">
    <?php echo CHtml::submitButton('Nhận mã OTP', array('class' => 'btn bg_btn')); ?>
</div>
<?php $this->endWidget(); ?>
<div class="space_10"></div>

<script>
    $('#modal_roaming').unbind('submit').on('submit', '#form_send_otp_ir_only', function (e) {
        e.preventDefault();
        $(':input[type="submit"]').prop('disabled', true);
        // this.submit();
        $.ajax({
            url: $(this).attr('action'),
            crossDomain: true,
            type: $(this).attr('method'),
            cache: false,
            dataType: "json",
            data: $(this).serialize(),
            success: function (result) {
                $(':input[type="submit"]').prop('disabled', false);
                $('#modal_roaming .modal-body').html(result.content);
            },
            error: function (request, status, err) {
                $(':input[type="submit"]').prop('disabled', false);
            }
        });
    });
</script>