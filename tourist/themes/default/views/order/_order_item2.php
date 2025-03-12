<?php
/**
 * @var $this OrderController
 * @var $package TPackage
 * @var $order TOrders
 * @var $contract_detail TContractsDetails
 * @var $row int
 */

$check = ($order->packages == $package->id) ? true : false;
$quantity = ($order->packages == $package->id) ? $order->quantity : 0
?>

<tr class="order-item" data-id="<?php echo $package->id?>" data-check="<?php echo ($check) ? 1 : 0?>">
    <td class="action">
        <?php echo CHtml::radioButton('TOrders[packages]', $check, array(
            'class' => 'packages_check',
            'value' => $package->id,
        ))?>
    </td>
    <td class="item-name">
        <label><?php echo CHtml::encode($name = $package->name)?></label>
    </td>
    <td class="item-price" data-value="<?php echo $price = $package->price?>">
        <label><?php echo CHtml::encode(number_format($price, 0, ',', '.') . ' đ') ?></label>
    </td>

    <?php
    $discount       = TContractsDetails::getItemDiscount($contract_detail);
    $discount_type  = TContractsDetails::getItemDiscountType($contract_detail);
    $discount_str   = TContractsDetails::getItemDiscountString($contract_detail);
    if($order->use_promo_code) {
        $discount = 0;
        $discount_str = '0';
    }
    ?>

    <td class="item-discount" data-value="<?php echo $discount?>" data-type="<?php echo $discount_type?>">
        <label><?php echo CHtml::encode($discount_str) ?></label>
    </td>
    <td class="item-quantity-remain" data-value="<?php echo $remain = $contract_detail->quantity - TOrders::getItemUsedQuantity($order->contract_id, $package->id)?>">
        <label><?php echo $remain - $quantity ?></label>
    </td>
</tr>
