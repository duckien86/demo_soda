<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $model ACardStoreBusiness
 * @var $data array ACardStoreBusiness
 */

$th_style = 'text-align:center;vertical-align:middle;';
$td_style = 'text-align:center;vertical-align:middle;';
$empty = "<tr><td class='empty' colspan='5'><span class='empty'>Không có dữ liệu.</td></tr>";
?>

<div id="card_store_business_report_import-grid" class="grid-view">
    <table class="table table-bordered table-striped table-hover jambo_table responsive-utilities">
        <thead>
        <tr>
            <th rowspan="2" style="<?php echo $th_style?>width: 120px;">Mệnh giá thẻ</th>
            <th rowspan="2" style="<?php echo $th_style?>width: 120px;">SL tồn đầu kỳ</th>
            <th rowspan="2" style="<?php echo $th_style?>width: 120px;">SL nhập trong kỳ</th>
            <th colspan="2" style="<?php echo $th_style?>">SL xuất kho trong kỳ</th>
            <th rowspan="2" style="<?php echo $th_style?>width: 120px;">SL tồn kho cuối kỳ</th>
        </tr>
        <tr>
            <th style="<?php echo $th_style?>width: 140px;">Kích hoạt thành công</th>
            <th style="<?php echo $th_style?>width: 140px;">Kích hoạt thất bại</th>
        </tr>
        </thead>

        <tbody>
            <?php
            if(empty($data)){
                echo $empty;
            }else{
                $row = 1;
                foreach ($data as $item) {
                    $tr_class = ($row%2 == 0) ? 'even' : 'odd';
                    $remain_before  = ACardStoreBusiness::getCardQuantityByValue($item->value, null, 'remain_before', $model->start_date, $model->end_date);
                    $import         = ACardStoreBusiness::getCardQuantityByValue($item->value, null, 'import', $model->start_date, $model->end_date);
                    $export_success = ACardStoreBusiness::getCardQuantityByValue($item->value, ACardStoreBusiness::CARD_SUCCESS, 'export', $model->start_date, $model->end_date);
                    $export_fail    = ACardStoreBusiness::getCardQuantityByValue($item->value, ACardStoreBusiness::CARD_FAILED, 'export', $model->start_date, $model->end_date);
                    $export_pending = ACardStoreBusiness::getCardQuantityByValue($item->value, ACardStoreBusiness::CARD_PENDING, 'export', $model->start_date, $model->end_date);
                    $export_active  = ACardStoreBusiness::getCardQuantityByValue($item->value, ACardStoreBusiness::CARD_ACTIVATED, 'export', $model->start_date, $model->end_date);
                    $remain_after   = ACardStoreBusiness::getCardQuantityByValue($item->value, null, 'remain_after', $model->end_date, $model->end_date);
                    ?>
                    <tr class="<?php echo $tr_class?>">

                        <td style="<?php echo $td_style?>">
                            <?php echo CHtml::encode(number_format($item->value,0,',','.'). ' VND') ?>
                        </td>

                        <td style="<?php echo $td_style?>">
                            <?php echo CHtml::encode(number_format($remain_before,0,',','.'));?>
                        </td>

                        <td style="<?php echo $td_style?>">
                            <?php echo CHtml::encode(number_format($import,0,',','.'));?>
                        </td>

                        <td style="<?php echo $td_style?>">
                            <?php echo CHtml::encode(number_format($export_success+$export_active,0,',','.'));?>
                        </td>

                        <td style="<?php echo $td_style?>">
                            <?php echo CHtml::encode(number_format($export_fail,0,',','.'));?>
                        </td>

                        <td style="<?php echo $td_style?>">
                            <?php echo CHtml::encode(number_format($remain_after,0,',','.'));?>
                        </td>
                    </tr>
                    <?php
                    $row++;
                }
            }
            ?>
        </tbody>
    </table>
</div>