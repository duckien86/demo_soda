<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $details AFTOrderDetails[]
 * @var $contract AFTContracts
 *
 * @var $detail AFTOrderDetails
 */

?>
<table id="table_card_export" class="items items table table-bordered table-condensed table-striped">
    <thead>
    <tr>
        <th><?php echo Yii::t('adm/label','card_price');?></th>
        <th><?php echo Yii::t('adm/label','discount');?></th>
        <th><?php echo Yii::t('adm/label','sale_price');?></th>
        <th><?php echo Yii::t('adm/label','quantity_inventory');?></th>
        <th><?php echo Yii::t('adm/label','quantity_request');?></th>
        <th><?php echo Yii::t('adm/label','quantity_missing');?></th>
        <th><?php echo Yii::t('adm/label','total_success');?></th>
        <th><?php echo Yii::t('adm/label','total_fail');?></th>
    </tr>
    </thead>
    <tbody>
    <?php if(!empty($details)){
        foreach ($details as $detail){
            $card = AFTPackage::model()->findByPk($detail->item_id);

            $remain = ACardStoreBusiness::getCardQuantityByValue($card->price, ACardStoreBusiness::CARD_NEW);
            $total_success = ACardStoreBusiness::getQuantityCardExport($detail->order_id, $card->price, array(
                ACardStoreBusiness::CARD_ACTIVATED,
                ACardStoreBusiness::CARD_SUCCESS,
            ));
            $total_fail = ACardStoreBusiness::getQuantityCardExport($detail->order_id, $card->price,
                ACardStoreBusiness::CARD_FAILED
            );
            $missing = $detail->quantity-$total_success;
            ?>
                <tr>
                    <td><?php echo CHtml::encode(number_format($card->price,0,',','.') . ' VND')?></td>
                    <td><?php echo CHtml::encode(AFTContractsDetails::getDiscountLabel($detail->order_id, $detail->item_id))?></td>
                    <td><?php echo CHtml::encode(number_format($detail->price,0,',','.') . ' VND')?></td>
                    <td><?php echo CHtml::encode(number_format($remain,0,',','.'))?></td>
                    <td><?php echo CHtml::encode(number_format($detail->quantity,0,',','.'))?></td>
                    <td class="text-warning">
                        <b><?php echo CHtml::encode(number_format($missing,0,',','.'))?></b>
                        <?php echo CHtml::hiddenField("AFTOrders[card][$card->price]",$missing)?>
                    </td>
                    <td class="text-success"><b><?php echo CHtml::encode(number_format($total_success,0,',','.'))?></b></td>
                    <td class="text-danger"><b><?php echo CHtml::encode(number_format($total_fail,0,',','.'))?></b></td>
                </tr>
            <?php
        }
    }else{ ?>
        <tr>
            <td colspan="8"><?php echo Yii::t('adm/label', 'empty_card');?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<style>
    tbody td{
        text-align: right;
    }
</style>