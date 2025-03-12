<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $data array ACardStoreBusiness
 * @var $card ACardStoreBusiness
 */
if(!isset($data)){
    $data = array();
}
?>

<?php echo $this->renderPartial('/aCardStoreBusiness/_table_card_summary', array('data' => $data));?>

<div class="space_10"></div>

<label><?php echo Yii::t('adm/label','quantity')?>: <span id="cardQuantity"><?php echo count($data)?></span></label>
<div id="tableCard_container">
<table id="tableCard" class="table table-bordered table-striped table-hover jambo_table responsive-utilities table">
    <thead>
        <tr>
            <th>STT</th>
            <th><?php echo CHtml::encode(Yii::t('adm/label','serial'))?></th>
            <th><?php echo CHtml::encode(Yii::t('adm/label','card_pin'))?></th>
            <th><?php echo CHtml::encode(Yii::t('adm/label','card_price'))?></th>
            <th><?php echo CHtml::encode(Yii::t('adm/label','expire_date'))?></th>
        </tr>
    </thead>
    <tbody>
        <?php if(empty($data)){
            echo "<tr><td colspan='5'>".Yii::t('adm/label','empty_card')."</td></tr>";
        }else{
            $row = 0;
            foreach ($data as $card){ ?>
                <tr>
                    <td><?php echo ++$row;?></td>
                    <td>
                        <?php echo CHtml::encode($card->serial)?>
                        <input name="ACardStoreBusiness[data][<?php echo $row?>][serial]" type="hidden" value="<?php echo $card->serial?>" />
                    </td>
                    <td>
                        <?php echo CHtml::encode($card->pin)?>
                        <input name="ACardStoreBusiness[data][<?php echo $row?>][pin]" type="hidden" value="<?php echo $card->pin?>" />
                    </td>
                    <td>
                        <?php echo CHtml::encode(number_format($card->value,0,',','.'))?>
                        <input name="ACardStoreBusiness[data][<?php echo $row?>][value]" type="hidden" value="<?php echo $card->value?>" />
                    </td>
                    <td>
                        <?php echo date("d-m-Y H:i:s", strtotime($card->expired_date))?>
                        <input name="ACardStoreBusiness[data][<?php echo $row?>][expired_date]" type="hidden" value="<?php echo date("d-m-Y H:i:s",strtotime($card->expired_date))?>" />
                    </td>
                </tr>
            <?php }
        }?>
    </tbody>
</table>
</div>
<style>
    #tableCard_container{
        max-height: 250px;
        overflow-y: auto;
    }
</style>