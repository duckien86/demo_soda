<?php
/* @var $this CheckoutController */
/* @var $modelOrder WOrders */
/* @var $modelSim WSim */
/* @var $modelPackage WPackage */
/* @var $amount */
?>
<div class="adr-oms panel" id="sim_order">
    <div class="header">
        <h3 class="title uppercase">
            Thông tin đơn hàng
        </h3>

        <div class="line"></div>
    </div>
    <div class="body">
        <!-- end table_order_header -->
        <table id="order_price_temp" class="order_price_temp adr-oms table table_order_items">
            <tbody>
            <tr class="gray_bg hidden_xs">
                <td colspan="2">
                    <?php $type_sim = Yii::app()->session['orders_data']->sim_type; ?>
                    <?php if($type_sim == WOrders::ESIM){ ?>
                        <div class="thumbnail_esim">
                            <span class="txt_strong"><?= CHtml::encode($modelSim->msisdn); ?></span>
                        </div>
                    <?php }else{?>
                        <div class="thumbnail">
                            <span class="txt_strong"><?= CHtml::encode($modelSim->msisdn); ?></span>
                        </div>
                    <?php }?>
                </td>
            </tr>
            <tr class="gray_bg">
                <td class="fl w50">
                    <span class="txt_label">
                        Số thuê bao
                    </span>
                </td>
                <td class="fl txt_value text-bold" >
                     <span>
                        <?= CHtml::encode($modelSim->msisdn); ?>
                     </span>
                </td>
            </tr>
            <tr class="gray_bg">
                <td class="fl w50">
                    <span class="txt_label" >
                        Loại thuê bao
                    </span>
                </td>
                <td class="fl txt_value">
                     <span id="order_sim_type_txt" class="text-bold" >
                        <?= ($modelSim->type == WSim::TYPE_POSTPAID) ? Yii::t('web/portal', 'postpaid') : Yii::t('web/portal', 'prepaid') ?>
                     </span>
                </td>
            </tr>
            <tr class="gray_bg">
                <td class="fl w50">
                    <span class="txt_label">
                        Tên gói cước
                    </span>
                </td>
                <td class="fl txt_value">
                    <span id="order_package_name" class="text-bold">
                        <?= (isset($modelPackage->name)) ? CHtml::encode($modelPackage->name) : ''; ?>
                    </span>
                </td>
            </tr>
            <?php if ($modelSim->type == WSim::TYPE_POSTPAID && isset($modelSim->price_term) && ($modelSim->price_term > 0)): ?>
                <tr class="gray_bg">
                    <td class="fl w50">
                    <span class="txt_label">
                        Cước cam kết :
                    </span>
                    </td>
                    <td class="fl txt_value">
                     <span class="text-bold">
                        <?= CHtml::encode(number_format($modelSim->price_term, 0, "", ".")); ?> đ/ tháng</span>
                    </td>
                </tr>
            <?php endif; ?>
            <tr  class="gray_bg hidden_xs">
                <td>
                    <div class="line"></div>
                </td>
            </tr>
            <tr>
                <td class="fl w50">
                    <span class="txt_label">
                        <?php if($type_sim){ ?>
                            Phí hòa mạng :
                        <?php }else{?>
                            Giá sim :
                        <?php }?>
                    </span>
                </td>
                <td class="fl txt_value">
                    <?php echo CHtml::hiddenField('order_sim_type', $modelSim->type); ?>
                    <?php echo CHtml::hiddenField('order_sim_price', $modelSim->price); ?>
                    <span id="order_sim_price_txt">
                        <?= CHtml::encode(number_format($modelSim->price, 0, "", ".")); ?>
                     </span>đ
                </td>
            </tr>
            <?php if ($modelSim->type == WSim::TYPE_POSTPAID && isset($modelSim->price_term) && ($modelSim->price_term > 0)): ?>
                <tr>
                    <td class="fl w50">
                    <span class="txt_label">
                        Tiền đặt cọc:
                    </span>
                    </td>
                    <td class="fl txt_value">
                     <span id="">
                        <?= CHtml::encode(number_format($modelSim->price_term, 0, "", ".")); ?>
                     </span>đ
                    </td>
                </tr>
            <?php endif; ?>
            <?php if ($modelSim->type == WSim::TYPE_PREPAID && isset($modelPackage->price)): ?>
                <tr>
                    <td class="fl w50">
                    <span class="txt_label">
                        Giá gói cước :
                    </span>
                    </td>
                    <td class="fl txt_value">
                    <span id="order_package_price">
                        <?= isset($modelPackage->price) ? CHtml::encode(number_format($modelPackage->price, 0, "", ".")) . ' đ' : ''; ?>
                    </span>
                    </td>
                </tr>
            <?php endif; ?>
            <tr>
                <td class="fl w50">
                    <span class="txt_label">
                        Phí giao hàng :
                    </span>
                </td>
                <td class="fl txt_value">
                    <?php
                    $order_ship_price = 0;
                    if ($modelOrder->delivery_type == WOrders::DELIVERY_TYPE_HOME) {
                        $order_ship_price = $GLOBALS['config_common']['order']['price_ship'];
                    }
                    echo CHtml::hiddenField('order_ship_price', $order_ship_price);
                    ?>
                    <span id="order_ship_price_txt">
                        <?= ($order_ship_price) ? (number_format($order_ship_price, 0, "", ".")) . ' đ' : 'Miễn phí'; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="fl w50">
                    <span class="txt_label">
                        Giảm giá :
                    </span>
                </td>
                <td class="fl txt_value">
                    <span>
                        <?php if($type_sim){ ?>
                            Miễn phí eSIM
                        <?php }else{?>
                            0 đ
                        <?php }?>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="fl lbl_amount w50">
                    <span class="txt_label text-bold" >
                        Tổng giá trị đơn hàng :
                    </span>
                </td>
                <td class="fl txt_value col_amount text-bold" >
                    <?= CHtml::hiddenField('package_amount', 0); ?>
                    <?= CHtml::hiddenField('card_amount', 0); ?>
                    <?= CHtml::hiddenField('total_amount', $amount); ?>
                    <span id="order_total_amount">
                        <?= CHtml::encode(number_format($amount, 0, "", ".")); ?>
                    </span>đ
                </td>
            </tr>
            <tr>
                <td class="fl note">
                    <?php if ($modelSim->type == WSim::TYPE_POSTPAID): ?>
                        <?php if (isset($modelSim->price_term) && ($modelSim->price_term > 0)): ?>
                            <p>* Tiền đặt cọc được trừ vào hoá đơn cước tháng cuối cùng của thời gian cam kết.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <!--<p>* Quý khách vui lòng nạp thẻ bất kỳ sau khi kích hoạt để sử dụng.</p>-->
                    <?php endif; ?>
                    <p>* Phí giao hàng được chi trả cho nhân viên giao nhận và không bao gồm trong hóa đơn GTGT.</p>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="space_1"></div>
    </div>
</div>
<!-- end #panel_order -->