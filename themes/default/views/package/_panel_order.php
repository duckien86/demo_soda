<?php
    /* @var $this PackageController */
    /* @var $modelOrder WOrders */
    /* @var $modelPackage WPackage */
?>
<div class="adr-oms panel" id="panel_order">
    <div class="header">
        <h3 class="title uppercase">
            Thông tin đơn hàng
        </h3>

        <div class="line"></div>
    </div>
    <div class="body">
        <!-- end table_order_header -->
        <table class="adr-oms table table_order_items">
            <tbody>
            <tr class="border_bottom">
                <td class="fl">
                    <span class="txt_label">
                        Thuê bao mua gói cước:
                    </span>
                </td>
                <td class="fr txt_value">
                     <span id="order_phone">
                        <?= CHtml::encode($modelOrder->phone_contact); ?>
                     </span>
                </td>
            </tr>
            <?php if ($modelPackage->name): ?>
                <tr class="border_bottom">
                    <td class="fl">
                    <span class="txt_label">
                        Tên gói cước:
                    </span>
                    </td>
                    <td class="fr txt_value">
                    <span id="order_product">
                        <?= CHtml::encode($modelPackage->name); ?>
                    </span>
                    </td>
                </tr>
            <?php endif; ?>
            <tr class="border_bottom">
                <td class="fl">
                    <span class="txt_label">
                        Giá gói:
                    </span>
                </td>
                <td class="fr txt_value">
                    <?= CHtml::hiddenField('total_amount', 0); ?>
                    <span id="order_amount">
                        <?= CHtml::encode(number_format(($modelPackage->price), 0, "", ".")); ?>
                    </span>đ
                </td>
            </tr>
            <tr>
                <td class="fl lbl_amount">
                    <span class="txt_label">
                        Tổng giá trị đơn hàng
                    </span>
                </td>
                <td class="fr txt_value col_amount">
                    <span id="order_total_amount">
                        <?= CHtml::encode(number_format(($modelPackage->price), 0, "", ".")); ?>
                    </span>đ
                </td>
            </tr>

            <!-- end row --></tbody>
        </table>
    </div>
</div>
<!-- end #panel_order -->