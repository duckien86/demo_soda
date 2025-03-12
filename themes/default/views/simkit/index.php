<?php
/**
 * @var $this SimKitController
 * @var $list_package array
 */
$start  = 1;
$limit  = 2;
$open   = '<div class="row">';
$close  = '</div>';
$isOpen = false;
?>
<div id="simkit">
    <?php $this->renderPartial('/simkit/_banner'); ?>

    <div class="container">
        <div id="list_simkit">
            <?php foreach ($list_package as $package) {
                if($start == 1){
                    echo $open;
                    $isOpen = true;
                }
                if($start <= $limit){
                    echo $this->renderPartial('/simkit/_item_simkit', array(
                        'model' => $package,
                    ));
                }
                if($start == $limit){
                    echo $close;
                    $isOpen = false;
                    $start = 1;
                }else{
                    $start++;
                }
            }
            if($isOpen){
                echo $close;
            }?>
        </div>
    </div>

</div>
