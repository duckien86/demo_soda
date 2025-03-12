<?php
    /* @var $this PackageController */
    /* @var $modelOrder WOrders */
    /* @var $modelPackage WPackage */
    /* @var $package_flexible */
?>
<div class="adr-oms panel" id="panel_order">
    <div class="header">
        <h3 class="title uppercase">
            Thông tin đơn hàng
        </h3>

        <div class="line"></div>
    </div>
    <div class="body">
        <table class="adr-oms table table_order_items">
            <tbody id="order_flexible_table">
            <?php $this->renderPartial('_order_flexible_table', array(
                'modelOrder'       => $modelOrder,
                'modelPackage'     => $modelPackage,
                'package_flexible' => $package_flexible,
            )); ?>
            </tbody>
        </table>
    </div>
</div>
<!-- end #panel_order -->