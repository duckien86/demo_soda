<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $contract_details array AFTContractsDetails
 * @var $detail AFTContractsDetails
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
    </tr>
    </thead>
    <tbody>
    <?php if(!empty($contract_details)){
        foreach ($contract_details as $detail){
            $card = AFTPackage::model()->findByPk($detail->item_id);
            $price = $card->price;
            $discount = 0;
            if($detail->price_discount_percent){
                $price -= ($price * $detail->price_discount_percent /100);
                $discount = $detail->price_discount_percent . '%';
            }else if($detail->price_discount_amount){
                $price -= $detail->price_discount_amount;
                $discount = number_format($detail->price_discount_amount,0,',','.') . ' VND';
            }
            $remain = ACardStoreBusiness::getCardQuantityByValue($card->price, ACardStoreBusiness::CARD_NEW);
            ?>
            <tr>
                <td><?php echo CHtml::encode(number_format($card->price,0,',','.')). ' VND'?></td>
                <td><?php echo CHtml::encode($discount)?></td>
                <td><?php echo CHtml::encode(number_format($price,0,',','.')) . ' VND';?></td>
                <td><?php echo CHtml::encode(number_format($remain))?></td>
                <td>
                    <?php echo CHtml::encode(number_format($detail->quantity,0,',','.'));?>
                    <?php echo CHtml::hiddenField("AFTOrders[card][$card->price]", $detail->quantity)?>
                </td>
            </tr>
            <?php
        }
    }else{ ?>
        <tr>
            <td colspan="5"><?php echo Yii::t('adm/label', 'empty_card');?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
