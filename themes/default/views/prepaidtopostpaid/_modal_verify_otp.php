<?php
/**
 * @var $this PrepaidtopostpaidController
 * @var $model WPrepaidToPostpaid
 * @var $otpForm OtpForm
 * @var $form TbActiveForm
 */
?>
<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array(
        'id'       => 'modal_ptp_verify_otp',
        'autoOpen' => TRUE,
    )
); ?>

<div class="modal-body">
<!--    <a class="close" data-dismiss="modal">&times;</a>-->
    <img src="<?php echo Yii::app()->theme->baseUrl ?>/images/ptp_popup_verify_otp-min.png">

    <div id="modal_ptp_verify_otp_title" class="modal_ptp_content">
        Quý khách vui lòng nhập mã xác nhận đã được chúng tối gửi đến số điện thoại liên hệ
        <?php if(YII_DEBUG){
            echo "<br/>$model->otp";
        }?>
    </div>

    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'=>'prepaidtopostpaid_verify_otp-form',
        'method' => 'post',
        // 'enableAjaxValidation' => true,
        // 'enableClientValidation' => true,
        'action' => Yii::app()->createUrl('prepaidtopostpaid/verifyTokenKey'),
        'htmlOptions' => array(),
    )); ?>

    <?php echo $form->textField($otpForm, 'token', array(
        'class' => 'form-control',
        'required' => true,
        'autofocus' => true,
        'placeholder' => 'OTP',
    ))?>
    <div id="token_error"><?php echo $form->error($otpForm, 'token') ?></div>

    <?php echo CHtml::submitButton(Chtml::encode('Xác thực'), array(
        'class' => 'btn',
        'id'    => 'btnVerifyToken',
    ))?>

    <?php $this->endWidget()?>
</div>

<?php $this->endWidget()?>

<script>
    $('#modal_ptp_verify_otp').on('hide.bs.modal', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        return false;
    });
</script>
