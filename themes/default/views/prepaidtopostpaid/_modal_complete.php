<?php
/**
 * @var $this PrepaidtopostpaidController
 * @var $response_code int
 * @var $response_msg string
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
    <a class="close" data-dismiss="modal">&times;</a>
    <img src="<?php echo Yii::app()->theme->baseUrl ?>/images/ptp_popup_complete-min.png">

    <div id="modal_ptp_complete_title" class="modal_ptp_content">
        <?php if($response_code && intval($response_code) == 1){
            echo CHtml::encode("Chúc mừng bạn đã đăng ký chuyển đổi hình thức thuê bao thành công. Vinaphone sẽ liên hệ với bạn để hoàn thiện thủ tục");
        }else{
            if($response_code){
                echo CHtml::encode("Đăng ký chuyển đổi thất bại!");
                echo "<br/>". ucfirst($response_msg);
            }else{
                echo CHtml::encode("Đăng ký chuyển đổi thất bại!");
            }
        }?>
    </div>
</div>

<?php $this->endWidget()?>