<?php
    /* @var $this OrdersController */
    /* @var $modelOrders WOrders */
    /* @var $modelSearch SearchOrderForm */
    /* @var $orders */
    /* @var $msg */
?>
<div class="space_10"></div>
<?php $this->renderPartial('_filter_area_ssoid', array('modelSearch' => $modelSearch)); ?>

<div id="list_order_results">
    <?php $this->renderPartial('_list_order', array('orders' => $orders, 'msg' => $msg)); ?>
</div>
