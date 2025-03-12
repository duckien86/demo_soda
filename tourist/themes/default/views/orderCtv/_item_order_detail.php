<?php
/**
 * @var $item array: id, name, quantity, discount, discount_type, price, total_price,
 */
?>

<div class="item-order-detail">
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <?php echo CHtml::encode(Yii::t('tourist/label', 'product'))?>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 content">
            <b><?php echo CHtml::encode($item['name'])?></b>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <?php echo CHtml::encode(Yii::t('tourist/label', 'quantity'))?>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 content">
            <?php echo CHtml::encode($item['quantity'])?> kit
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <?php echo CHtml::encode(Yii::t('tourist/label', 'price'))?>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 content">
            <?php echo CHtml::encode(number_format($item['price'], 0, '.', ',') . 'đ')?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <?php echo CHtml::encode(Yii::t('tourist/label', 'discount'))?>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 content">
            <?php echo CHtml::encode($item['discount'].TContractsDetails::getDiscountTypeLabel($item['discount_type'])) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <?php echo CHtml::encode(Yii::t('tourist/label', 'item_total_price'))?>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 content">
            <?php echo CHtml::encode(number_format($item['total_price'], 0, '.', ',') . 'đ')?>
        </div>
    </div>
</div>
