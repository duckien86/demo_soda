<?php
/**
 * @var $this OrderController
 * @var $list_packages array
 * @var $order TOrders
 */
?>
<table id="contract-package" class="table table-striped table-responsive">
    <thead>
        <tr>
            <td scope="col">#</td>
            <td scope="col"><?php echo CHtml::encode(Yii::t('tourist/label', 'name'))?></td>
            <td scope="col"><?php echo CHtml::encode(Yii::t('tourist/label', 'price_short'))?></td>
            <td scope="col"><?php echo CHtml::encode(Yii::t('tourist/label', 'discount'))?></td>
            <td scope="col"><?php echo CHtml::encode(Yii::t('tourist/label', 'quantity_remain'))?></td>
            <td scope="col"><?php echo CHtml::encode(Yii::t('tourist/label', 'quantity'))?></td>
            <td scope="col"><?php echo CHtml::encode(Yii::t('tourist/label', 'item_total_price'))?></td>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=0;
        $order_total_price = 0;
        foreach ($list_packages as $package) {
            $contract_detail = TContractsDetails::getContractDetailByItemId($order->contract_id, $package->id);
            $this->renderPartial('/order/_order_item', array(
                'package' => $package,
                'order'   => $order,
                'contract_detail' => $contract_detail,
                'row' => ++$i,
            ));
            $order_total_price += TOrders::getItemTotalPrice($order, $package, $contract_detail,($order->packages[$package->id]['quantity']));

        }
        echo $this->getBlockOrderScript();
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" class="text-center"><?php echo CHtml::encode(Yii::t('tourist/label','order_total_price'))?></td>
            <td colspan="1" class="order-total-price">
                <label><?php echo CHtml::encode(number_format($order_total_price, 0, '.', ',') . ' đ') ?></label>
            </td>
        </tr>
    </tfoot>
</table>

