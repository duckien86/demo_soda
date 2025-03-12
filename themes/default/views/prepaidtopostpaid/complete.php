<?php
/**
 * @var $this PrepaidtopostpaidController
 * @var $model WPrepaidToPostpaid
 * @var $province array
 * @var $district array
 * @var $ward array
 * @var $list_package array
 * @var $response_code int
 * @var $response_msg string
 */
$this->pageTitle = Yii::t('web/portal','prepaid_to_postpaid');
?>
<div id="prepaidtopostpaid">
    <section class="ss-bg">
        <section class="ss-box1">
            <div class="container no_pad_xs">
                <div class="ss-box1-right-all">
                    <?php if($response_code && intval($response_code) == 1){
                        echo "<h3>".CHtml::encode("Chúc mừng bạn đã đăng ký chuyển đổi hình thức thuê bao thành công. Vinaphone sẽ liên hệ với bạn để hoàn thiện thủ tục")."</h3>";
                    }else{
                        if($response_code){
                            echo "<h3>".CHtml::encode("Đăng ký chuyển đổi thất bại!")."</h3>";
                            echo "<br/>". ucfirst($response_msg);
                        }else{
                            echo CHtml::encode("Đăng ký chuyển đổi thất bại!");
                        }
                    }?>
                    <div class="action">
                    <?php echo CHtml::link(Yii::t('adm/label', 'back_home'), Yii::app()->controller->createUrl('site/index'), array(
                        'class' => 'btn',
                        'style' => 'background: #ed0977;color: #fff;outline: none; margin-top:20px',
                    )); ?>
                    </div>
                </div>
            </div>
        </section>
    </section>
</div>
