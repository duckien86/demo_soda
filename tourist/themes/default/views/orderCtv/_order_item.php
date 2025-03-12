<?php
/**
 * @var $this OrderCtvController
 * @var $package TPackage
 * @var $order TOrders
 * @var $contract_detail TContractsDetails
 * @var $row int
 */
?>

<tr class="order-item" data-id="<?php echo $package->id?>">
    <td><?php echo CHtml::encode($row) ?></td>
    <td class="item-name">
        <label><?php echo CHtml::encode($name = $package->name)?></label>
        <?php echo CHtml::hiddenField("TOrders[packages][$package->id][name]", $name)?>
    </td>
    <td class="item-price" data-value="<?php echo $price = $package->price?>">
        <label><?php echo CHtml::encode(number_format($price, 0, ',', '.') . ' đ') ?></label>
        <?php echo CHtml::hiddenField("TOrders[packages][$package->id][price]", $price)?>
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

    <td class="item-quantity">
        <?php echo CHtml::numberField("TOrders[packages][$package->id][quantity]", ($order->packages[$package->id]['quantity']) ? $order->packages[$package->id]['quantity'] : 0, array(
            'class' => 'form-control packages_quantity form-item',
            'min'   => 0,
            'oninput' => 'loadContractRemainAmount(this)'
            // 'style' => 'width: 100%'
        )) ?>
    </td>
    <td class="item-total-price" data-value="<?php $total_price = TOrders::getItemTotalPrice($order, $package, $contract_detail,($order->packages[$package->id]['quantity']))?>">
        <label><?php echo CHtml::encode(number_format($total_price, 0, ',', '.') . ' đ') ?></label>
        <?php echo CHtml::hiddenField("TOrders[packages][$package->id][total_price]", $total_price)?>
    </td>
</tr>
