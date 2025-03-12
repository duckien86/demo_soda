<?php
    /* @var $this OrdersController */
    /* @var $modelSearch SearchOrderForm */
    /* @var $orders */
?>
<div class="container page_detail">
    <div class="space_10"></div>

    <div id="list_order_results">
        <?php $this->renderPartial('_list_order', array('orders' => $orders)); ?>
    </div>
</div>
<div class="space_30"></div>