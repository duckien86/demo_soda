<?php
/* @var $this CardController */
/* @var $modelOrder WOrders */
/* @var $orderDetails WOrderDetails */
/* @var $form CActiveForm */
/* @var $operation */
/* @var $amount */
?>

<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <div class="col-lg-9">
        <iframe src="https://vnptpay.vn/bill/frame_vinaphone" width="100%" height="1080px"></iframe>
    </div>
    <div class="col-lg-3">
        <video width="100%" controls autoplay>
            <source src="<?php echo Yii::app()->baseUrl?>/uploads/videos/intro.mp4" type="video/mp4">
        </video>
    </div>


</div>
<style>
    .box-white{
        border: none !important;
    }
    .em_subinfo{
        margin: 50px 0px !important;
    }
    iframe{
        border: none !important;
    }
</style>