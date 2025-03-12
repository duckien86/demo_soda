<?php
/**
 * @var $order_total_price int
 */
?>

<div class="row order-total-price">
    <div class="col-md-6 col-sm-6 col-xs-6">
        <?php echo CHtml::encode(Yii::t('tourist/label', 'order_total_price'))?>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6 content">
        <?php echo number_format($order_total_price, 0, '.', ',') . 'đ'?>
    </div>
</div>
