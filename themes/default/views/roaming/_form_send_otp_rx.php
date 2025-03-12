<?php
    /* @var $this RoamingController */
    /* @var $modelOrder WOrders */
    /* @var $modelPackage WPackage */
    /* @var $form CActiveForm */
    /* @var $msg */
?>
<script src='https://www.google.com/recaptcha/api.js?hl=vi'></script>
<div class="text-center font_25 lbl_color_blue">
    Đăng ký: <span><?= CHtml::encode($modelPackage->name); ?></span>
</div>
<div class="space_20"></div>
<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id'                   => 'form_send_otp_rx',
    'action'               => Yii::app()->controller->createUrl('roaming/sendOtpRx'),
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
    $('#modal_roaming').unbind('submit').on('submit', '#form_send_otp_rx', function (e) {
        var modal_roaming = $('#modal_roaming');
        var modal_body = $('#modal_roaming .modal-body');
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
    //    $(document).on('submit', '#form_send_otp', function (e) {
    //        var modal_roaming = $('#modal_roaming');
    //        var modal_body = $('#modal_roaming .modal-body');
    //        modal_roaming.unbind('submit');
    //        e.preventDefault();
    //        $(':input[type="submit"]').prop('disabled', true);
    //        console.log('send otp 1');
    //        // this.submit();
    //        $.ajax({
    //            url: $(this).attr('action'),
    //            crossDomain: true,
    //            type: $(this).attr('method'),
    //            cache: false,
    //            dataType: "json",
    //            data: $(this).serialize(),
    //            success: function (result) {
    //                console.log('send otp 2');
    //                $(':input[type="submit"]').prop('disabled', false);
    //                modal_body.html(result.content);
    //            },
    //            error: function (request, status, err) {
    //                $(':input[type="submit"]').prop('disabled', false);
    //            }
    //        });
    //    });
</script>