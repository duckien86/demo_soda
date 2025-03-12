<?php
/**
 * @var $this ACardStoreController
 * @var $data array ACardStore
 * @var $card ACardStore
 */
if(!isset($data)){
    $data = array();
}
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
    }
    ksort($summary);
}
?>


<div id="tableCardSummary_container">
<table id="tableCardSummary" class="table table-bordered table-striped table-hover jambo_table responsive-utilities table">
    <thead>
        <tr>
            <th>Mệnh giá</th>
            <?php
            if(empty($summary)){?>
                <th>10.000</th>
                <th>20.000</th>
                <th>50.000</th>
                <th>100.000</th>
                <th>200.000</th>
                <th>500.000</th>
            <?php
            }else{
                foreach ($summary as $value => $quantity){
                    echo "<th>".number_format($value,0,',','.')."</th>";
                }
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Số lượng</td>
            <?php
            if(empty($summary)){?>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
            <?php
            }else{
                foreach ($summary as $value => $quantity){
                    echo "<td>".number_format($quantity,0,',','.')."</td>";
                }
            }
            ?>
        </tr>
    </tbody>
</table>
</div>

<style>
    #tableCardSummary_container{
        width: 100%;
        overflow-x: auto;
    }
</style>