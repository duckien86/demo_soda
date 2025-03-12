<?php
/**
 * @var $this OrderCtvController
 * @var $list_packages array
 * @var $order TOrders
 * @var $package TPackage
 */
?>
<table id="contract-package" class="table table-striped table-responsive">
    <thead>
        <tr>
            <td scope="col"><?php echo CHtml::encode(Yii::t('tourist/label', 'select'))?></td>
            <td scope="col"><?php echo CHtml::encode(Yii::t('tourist/label', 'name'))?></td>
            <td scope="col"><?php echo CHtml::encode(Yii::t('tourist/label', 'price_short'))?></td>

        </tr>
    </thead>
    <tbody>
        <?php
        $i=0;
        $order_total_price = 0;
        foreach ($list_packages as $package) {

            $this->renderPartial('/orderCtv/_order_item2', array(
                'package' => $package,
                'order'   => $order,
                'row' => ++$i,
            ));

            if(!empty($order->packages) && !empty($order->quantity) && $order->packages == $package->id){
                $order_total_price = $order->quantity * $package->price;
            }
        }

        echo $this->getBlockOrderFsScript();
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="text-center"><?php echo CHtml::encode(Yii::t('tourist/label','order_total_price'))?></td>
            <td colspan="1" class="order-total-price">
                <label><?php echo CHtml::encode(number_format($order_total_price, 0, '.', ',') . ' đ') ?></label>
            </td>
        </tr>
    </tfoot>
</table>

