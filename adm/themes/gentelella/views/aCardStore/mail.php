<?php
/**
 * @var $data array ACardStore
 * @var $card ACardStore
 * @var $time string
 * @var $status int
 */
if(!isset($data)){
    $data = array();
}
$total = 0;
$summary = array();
if(!empty($data)){
    foreach ($data as $card){
        if(empty($summary)){
            $summary[$card->value] = 1;
        }else{
            if(isset($summary[$card->value])){
                $summary[$card->value]++;
            }else{
                $summary[$card->value] = 1;
            }
        }
        $total+= $card->value;
    }
    ksort($summary);
}
?>



<div id="card_store_mail">
    <div id="info">
        <p>Thời gian tạo: <?php echo $time?></p>
        <p>Trạng thái thẻ: Mới (chưa Active)</p>
        <p>Tổng giá trị: <?php echo number_format($total,0,',','.') . " VND"?></p>
    </div>

    <div id="summary">
        <table style="border: 1px solid #ccc; min-width: 700px">
            <thead>
            <tr>
                <th style="border: 1px solid #ccc;">Mệnh giá</th>
                <th style="border: 1px solid #ccc;">Số lượng</th>
                <th style="border: 1px solid #ccc;">Tổng</th>
            </tr>
            </thead>
            <tbody style="text-align: center">
            <?php foreach ($summary as $value => $quantity){?>
                <tr>
                    <td style="border: 1px solid #ccc;"><?php echo number_format($value,0,',','.') . ' VND'?></td>
                    <td style="border: 1px solid #ccc;"><?php echo number_format($quantity,0,',','.');?></td>
                    <td style="border: 1px solid #ccc;"><?php echo number_format($value*$quantity,0,',','.') . ' VND';?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>

<style>
    #card_store_mail{
        position: relative;
    }
    #info, #summary{
        width: 50%;
        display: inline-block;
        float: left;
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
        background-color: transparent;
    }
    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
    }
    table.table {
        margin: 0;
    }
    table.jambo_table {
        border: 1px solid rgba(221, 221, 221, 0.78);
    }
    table.jambo_table thead {
        background: rgba(52, 73, 94, 0.94);
        color: #ECF0F1;
    }
    .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>th, .table>caption+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>td, .table>thead:first-child>tr:first-child>td {
        border-top: 0;
    }
    .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
        border: 1px solid #ddd;
    }
    .table>thead>tr>th {
        vertical-align: bottom;
    }
    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        padding: 11px;
        line-height: 1.42857143;
    }
    .table-striped>tbody>tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }
    .table-hover>tbody>tr:hover {
        background-color: #f5f5f5;
    }

</style>