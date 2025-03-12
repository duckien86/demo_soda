<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $form TbActiveForm
 * @var $model AFTOrders
 * @var $details array
 * @var $detail AFTOrderDetails
 */
?>
<div id="tableOrderDetail_container">
    <h2>Thông tin đơn hàng</h2>

    <table class="items items table table-bordered table-condensed table-striped">
        <thead>
        <tr>
            <th><?php echo CHtml::encode(Yii::t('adm/label','product'))?></th>
            <th><?php echo CHtml::encode(Yii::t('adm/label','quantity'))?></th>
            <th><?php echo CHtml::encode(Yii::t('adm/label','sale_price'))?></th>
            <th><?php echo CHtml::encode(Yii::t('adm/label','item_total_price'))?></th>
            <th><?php echo CHtml::encode(Yii::t('adm/label','total_success'))?></th>
            <th><?php echo CHtml::encode(Yii::t('adm/label','total_fail'))?></th>
        </tr>
        </thead>

        <tbody>
        <?php
        $order_total = 0;
        $total_success = 0;
        $total_failed = 0;
        foreach ($details as $detail){
            $item_total = intval($detail->price) * $detail->quantity;
            $order_total += $item_total;
            $item = AFTPackage::model()->findByPk($detail->item_id);

            $item_total_success = ACardStoreBusiness::getQuantityCardExport($model->id, $item->price, array(
                ACardStoreBusiness::CARD_ACTIVATED,
                ACardStoreBusiness::CARD_SUCCESS
            ));
            $item_total_failed = ACardStoreBusiness::getQuantityCardExport($model->id, $item->price,
                ACardStoreBusiness::CARD_FAILED
            );

            $total_success+= $item_total_success;
            $total_failed+= $item_total_failed;
            ?>
            <tr>
                <td><?php echo Chtml::encode(AFTPackage::model()->getNameProduct($detail->item_id))?></td>
                <td><?php echo CHtml::encode(number_format($detail->quantity,0,',','.'))?></td>
                <td><?php echo CHtml::encode(number_format($detail->price,0,',','.') . ' VND')?></td>
                <td><?php echo CHtml::encode(number_format($item_total,0,',','.') . ' VND')?></td>

                <td><?php echo CHtml::encode(number_format($item_total_success,0,',','.'))?></td>
                <td><?php echo CHtml::encode(number_format($item_total_failed,0,',','.'))?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" class="text-left"><b><?php echo CHtml::encode(Yii::t('adm/label','total'))?></b></td>
            <td><?php echo CHtml::encode(number_format($order_total,0,',','.') . ' VND')?></td>
            <td><?php echo CHtml::encode(number_format($total_success,0,',','.'))?></td>
            <td><?php echo CHtml::encode(number_format($total_failed,0,',','.'))?></td>
        </tr>
        </tfoot>
    </table>


    <?php
    if($model->status == AFTOrders::ORDER_CARD_FAIL){
        echo CHtml::link('Cấp thêm thẻ',
            Yii::app()->createUrl('aCardStoreBusiness/update',array('order_id' => $model->id)),
            array(
                'class' => 'btn btn-warning',
                'style' => 'margin-top:15px'
            )
        );
    }
    ?>
</div>
