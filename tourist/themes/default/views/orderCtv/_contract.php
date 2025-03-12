<?php
/**
 * @var $this OrderCtvController
 * @var $contract TContracts
 * @var $list_contract_details array
 */
?>
<div class="modal_contract">
    <div class="contract_info">
        <h3><?php echo CHtml::encode(Yii::t('tourist/label','contract_info')) ?></h3>
        <table>
            <tr>
                <td><?php echo CHtml::encode(Yii::t('tourist/label', 'contract_code'))?></td>
                <td><?php echo CHtml::encode($contract->code)?></td>
            </tr>

            <tr>
                <td><?php echo CHtml::encode(Yii::t('tourist/label', 'contract_duration'))?></td>
                <td><?php echo CHtml::encode(date('d/m/Y', strtotime($contract->start_date)) . ' - ' . date('d/m/Y', strtotime($contract->finish_date))) ?></td>
            </tr>

            <tr>
                <td><?php echo CHtml::encode(Yii::t('tourist/label', 'status'))?></td>
                <td><?php echo CHtml::encode(TContracts::getContractStatusLabel($contract))?></td>
            </tr>

            <tr>
                <td><?php echo CHtml::encode(Yii::t('tourist/label', 'note'))?></td>
                <td><?php echo $contract->note ?></td>
            </tr>
        </table>
    </div>

    <div class="contract_info" style="margin-top: 10px">
        <h4><?php echo CHtml::encode(Yii::t('tourist/label','contract_detail')) ?></h4>
        <table class="table table-striped table-responsive">
            <thead>
            <tr>
                <td>#</td>
                <td><?php echo CHtml::encode(Yii::t('tourist/label', 'product'))?></td>
                <td><?php echo CHtml::encode(Yii::t('tourist/label', 'price'))?></td>
                <td><?php echo CHtml::encode(Yii::t('tourist/label', 'discount'))?></td>
                <td><?php echo CHtml::encode(Yii::t('tourist/label', 'quantity'))?></td>
                <td><?php echo CHtml::encode(Yii::t('tourist/label', 'remain'))?></td>
                <td><?php echo CHtml::encode(Yii::t('tourist/label', 'description'))?></td>
            </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($list_contract_details as $contract_detail){
                    $i++;
                    $package = TPackage::getPackage($contract_detail->item_id);
                    if($package){
                ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo CHtml::encode($package->name); ?></td>
                        <td><?php echo CHtml::encode(number_format($package->price, 0, ',', '.') . ' đ'); ?></td>
                        <td><?php echo CHtml::encode(TContractsDetails::getItemDiscountString($contract_detail)) ?></td>
                        <td><?php echo CHtml::encode($contract_detail->quantity) ?></td>
                        <td><?php echo CHtml::encode($contract_detail->quantity - TOrders::getItemUsedQuantity($contract->id, $package->id)) ?></td>
                        <td>
                            <?php
                            echo CHtml::link(Yii::t('tourist/label', 'detail'),'#', array(
                                'onClick' => 'loadProductDetail(\''.$package->id.'\',\''.$package->name.'\',\''.$package->description.'\')',
                            ));
                            ?>
                        </td>
                    </tr>
                <?php }} ?>
            </tbody>
        </table>
    </div>

    <div class="contract_info hidden" style="margin-top: 10px">
        <h4>Chi tiết sản phẩm</h4>
        <div id="contract_item_description">

        </div>
    </div>
</div>

<style>
#contract_item_description .item{
    border-top: #f9f9f9 2px dashed;
}
#contract_item_description .item:first-child{
    border-top: #f9f9f9 2px solid;
}
</style>

<script>
function loadProductDetail(id,name, description) {
    var html = '<div class="item" data-id="'+id+'">' +
        '<h5>'+name+'</h5>' +
        '<div class="content">'+description+'</div>' +
        '</div>';
    var container = $('#contract_item_description');

    container.closest('.contract_info').removeClass('hidden');
    container.find('.item[data-id='+id+']').remove();
    container.prepend(html);
}
</script>


