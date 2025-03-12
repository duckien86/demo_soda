<?php
    /* @var $this CardController */
    /* @var $modelOrder WOrders */
    /* @var $orderDetails WOrderDetails */
    /* @var $operation */
    /* @var $amount */

    if ($operation == OrdersData::OPERATION_BUYCARD) {
        $lbl_field_name  = 'Email nhận mã thẻ:';
        $lbl_field_value = CHtml::encode($modelOrder->email);
    } else {
        $lbl_field_name  = 'Thuê bao được nạp thẻ:';
        $lbl_field_value = CHtml::encode($modelOrder->phone_contact);
    }
?>
<div class="adr-oms panel" id="card_order">
    <div class="header">
        <h3 class="title uppercase">
            Thông tin đơn hàng
        </h3>

        <div class="line"></div>
    </div>
    <div class="body">
        <!-- end table_order_header -->
        <table id="order_price_temp" class="adr-oms table table_order_items">
            <tbody>
            <tr>
                <td colspan="2">
                    <div class="space_10"></div>
                    <div id="thumbnail"
                         class="<?= ($orderDetails->item_id) ? 'bg_' . $orderDetails->item_id : 'bg_10000'; ?>">
                        <span id="order_price_thumb">
                            <?php echo $orderDetails->item_id; ?>
                            <?= ($orderDetails->item_id) ? CHtml::encode(number_format($orderDetails->item_id, 0, "", ".")) : ''; ?>
                        </span>
                    </div>
                    <div class="space_10"></div>
                </td>
            </tr>
            <tr class="border_bottom">
                <td class="fl">
                    <span class="txt_label">
                        <?= $lbl_field_name; ?>
                    </span>
                </td>
                <td class="fr txt_value">
                     <span id="order_phone">
                        <?= $lbl_field_value; ?>
                     </span>
                </td>
            </tr>
            <tr class="border_bottom">
                <td class="fl">
                    <span class="txt_label">
                         Mệnh giá:
                    </span>
                </td>
                <td class="fr txt_value">
                     <span id="order_price">
                          <?= CHtml::encode(number_format($orderDetails->price, 0, "", ".")); ?>
                     </span>đ
                </td>
            </tr>
            <?php if ($operation == OrdersData::OPERATION_BUYCARD): ?>
                <tr class="border_bottom">
                    <td class="fl">
                    <span class="txt_label">
                         Số lượng:
                    </span>
                    </td>
                    <td class="fr txt_value">
                    <span id="order_quantity">
                         <?= CHtml::encode($orderDetails->quantity); ?>
                    </span>
                    </td>
                </tr>
            <?php endif; ?>
            <tr>
                <td class="fl lbl_amount">
                    <span class="txt_label">
                        Tổng giá trị đơn hàng
                    </span>
                </td>
                <td class="fr txt_value col_amount">
                    <span id="order_total_amount">
                        <?= CHtml::encode(number_format($amount, 0, "", ".")); ?>
                    </span>đ
                </td>
            </tr>
            </tbody>
        </table>
        <div class="space_30"></div>
    </div>
</div>
<!-- end #panel_order -->