<?php
/**
 * @var $this TopupController
 * @var $data array TTopupQueue
 * @var $topup TTopupQueue
 */
if(!isset($data)){
    $data = array();
}
?>
<label><?php echo Yii::t('tourist/label','quantity')?>: <span id="topupQuantity"><?php echo count($data)?></span></label>
<div id="tableTopup_container">
<table id="tableTopup" class="table table-bordered table-striped table-hover jambo_table responsive-utilities table">
    <thead>
        <tr>
            <th>STT</th>
            <th><?php echo CHtml::encode(Yii::t('tourist/label','msisdn'))?></th>
            <th><?php echo CHtml::encode(Yii::t('tourist/label','card_pin'))?></th>
        </tr>
    </thead>
    <tbody>
        <?php if(empty($data)){
            echo "<tr><td colspan='5'>".Yii::t('tourist/label','empty_topup')."</td></tr>";
        }else{
            $row = 0;
            foreach ($data as $topup){ ?>
                <tr>
                    <td><?php echo ++$row;?></td>
                    <td>
                        <?php echo CHtml::encode($topup->msisdn)?>
                        <input name="TTopupQueue[data][<?php echo $row?>][msisdn]" type="hidden" value="<?php echo $topup->msisdn?>" />
                    </td>
                    <td>
                        <?php echo CHtml::encode($topup->pin)?>
                        <input name="TTopupQueue[data][<?php echo $row?>][pin]" type="hidden" value="<?php echo $topup->pin?>" />
                    </td>
                </tr>
            <?php }
        }?>
    </tbody>
</table>
</div>
<style>
    #tableTopup_container{
        max-height: 400px;
        overflow-y: auto;
    }
</style>