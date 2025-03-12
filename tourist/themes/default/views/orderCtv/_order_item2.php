<?php
/**
 * @var $this OrderCtvController
 * @var $package TPackage
 * @var $order TOrders
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

</tr>
