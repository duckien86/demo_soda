<?php
    /* @var $this RoamingController */
    /* @var $modelOrder WOrders */
    /* @var $modelPackage WPackage */
    /* @var $form CActiveForm */
    /* @var $msg */
?>
<script src='https://www.google.com/recaptcha/api.js?hl=vi'></script>
<div class="text-center font_15 lbl_color_pink">
    Mã xác thực không đúng, vui lòng nhập lại SĐT để nhận mã
</div>
<div class="space_20"></div>
<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id'                   => 'form_send_otp_ir',
    'action'               => Yii::app()->controller->createUrl('roaming/sendOtpIr'),
    'enableAjaxValidation' => TRUE,
    'htmlOptions'          => array('onsubmit' => 'return false;'),
)); ?>
<?php echo $form->hiddenField($modelPackage, 'id'); ?>
<div class="col-md-2"></div>
<div class="col-md-8">
    <div class="form-group">
        <?php
            echo $form->textField($modelOrder, 'phone_contact', array(
                'class'       => 'textbox_lg',
                'maxlength'   => 255,
                'placeholder' => Yii::t('web/portal', 'input_phone_contact'),
                'onchange'  => 'changeMsisdnPrefix(this, null);',
            ));
        ?>
        <?php echo $form->error($modelOrder, 'phone_contact'); ?>
    </div>
    <div class="form-group">
        <div id="captcha_place_holder"
             class="g-recaptcha"
             data-sitekey="6LdnWS4UAAAAAAyy0Odc6bAuWs8wEm6BD9A6h66t"></div>
        <?php echo $form->error($modelOrder, 'captcha'); ?>
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
    //    $(document).on('submit', '#form_send_otp_ir', function (e) {
    $('#modal_roaming').unbind('submit').on('submit', '#form_send_otp_ir', function (e) {
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