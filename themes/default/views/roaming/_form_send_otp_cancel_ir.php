<?php
    /* @var $this RoamingController */
    /* @var $otpModel OtpForm */
    /* @var $form CActiveForm */
?>
<script src='https://www.google.com/recaptcha/api.js?hl=vi'></script>
<div class="text-center font_15">
    Quý khách muốn hủy CVQT, vui lòng nhập SĐT để nhận mã hủy
</div>
<div class="space_20"></div>
<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id'                   => 'form_send_otp_cancel_ir',
    'action'               => Yii::app()->controller->createUrl('roaming/sendOtpCancelIr'),
    'enableAjaxValidation' => TRUE,
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
    $('#modal_roaming').unbind('submit').on('submit', '#form_send_otp_cancel_ir', function (e) {
        var modal_roaming = $('#modal_roaming');
        var modal_body = $('#modal_roaming .modal-body');
//        modal_roaming.unbind('submit');//unbind button submit
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
                modal_body.html(result.content);
            },
            error: function (request, status, err) {
                $(':input[type="submit"]').prop('disabled', false);
            }
        });
    });
</script>