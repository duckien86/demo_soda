<?php
    /* @var $this RoamingController */
    /* @var $otpModel OtpForm */
    /* @var $form CActiveForm */
    /* @var $session_cart */
?>
<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id'                   => 'form_verify_otp_search_rx',
    'action'               => Yii::app()->controller->createUrl('roaming/searchRx'),
    'enableAjaxValidation' => TRUE,
    'htmlOptions'          => array('onsubmit' => 'return false;'),
)); ?>
<div class="text-center font_bold font_15">
    Quý khách vui lòng nhập mã OTP vừa nhận được qua SMS để tra cứu thông tin
</div>
<div class="space_20"></div>
<div class="col-md-2"></div>
<div class="col-md-8">
    <div class="form-group">
        <?php
            echo $form->textField($otpModel, 'token', array(
                'class'       => 'textbox_lg',
                'maxlength'   => 255,
                'placeholder' => Yii::t('web/portal', 'input_otp'),
            ));
        ?>
        <?php echo $form->error($otpModel, 'token'); ?>
    </div>
</div>
<div class="col-md-2"></div>
<div class="space_10"></div>
<div class="text-center font_15">
    <p>Thời gian còn lại <span id="count_down"></span></p>
</div>
<div class="space_20"></div>
<div class="package_info text-center">
    <?php echo CHtml::button('Hủy', array('data-dismiss' => 'modal', 'class' => 'close_modal btn btn-default width_100')); ?>
    <?php echo CHtml::submitButton('Đồng ý', array('class' => 'btn bg_btn width_100')); ?>
</div>

<?php $this->endWidget(); ?>
<div class="space_10"></div>
<?php $this->renderPartial('_count_down', array('session_cart' => $session_cart)); ?>

<script>
    $('#modal_roaming').unbind('submit').on('submit', '#form_verify_otp_search_rx', function (e) {
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