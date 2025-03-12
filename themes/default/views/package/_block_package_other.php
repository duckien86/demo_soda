<?php
/**
 * @var $this PackageController
 * @var $list_package array
 * @var $type int
 */

$open = '<div class="row">';
$openHidden = '<div class="row row-plus hidden">';
$close = '</div>';
$isOpen = false;
$start = 1;
$limit = 3;
$rowLimit = 2;
$rowStart = 1;

?>
<div class="block_package block_package_other">

    <?php foreach ($list_package as $package){
        if($start == 1){
            if($rowStart > $rowLimit){
                echo $openHidden;
            }else{
                echo $open;
            }

            $isOpen = true;
        }
        if($start <= $limit){
            $this->renderPartial('/package/_item_package_other', array(
                'model' => $package
            ));
        }
        if($start == $limit){
            echo $close;
            $isOpen = false;
            $rowStart ++;
            $start = 1;
        }else{
            $start++;
        }
    }
    if($isOpen){
        echo $close;
    }

    if(count($list_package) > ($limit*$rowLimit)){?>
        <div class="row action block_action text-center">
            <a class="btn btn-prev hidden" onclick="shortenPackage(this)">
                <i class="fa fa-angle-double-up"></i>
            </a>
            <a class="btn btn-next" onclick="showAllPackage(this)">
                <i class="fa fa-angle-double-down"></i>
            </a>
        </div>
    <?php }?>
</div>
