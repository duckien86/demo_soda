<?php
    /* @var $this CheckoutController */
    /* @var $modelOrder WOrders */
    /* @var $modelSim WSim */
    /* @var $modelPackage WPackage */
    /* @var $amount */
?>
<tbody>
<tr>
    <td colspan="2">
        <div class="thumbnail">
            <span class="txt_strong"><?= CHtml::encode($modelSim->msisdn); ?></span>
        </div>
    </td>
</tr>
<tr>
    <td class="fl">
            <span class="txt_label">
               Số thuê bao
            </span>
    </td>
    <td class="fr txt_value">
             <span>
                <?= CHtml::encode($modelSim->msisdn); ?>
             </span>
    </td>
</tr>
<tr>
    <td class="fl">
            <span class="txt_label">
                Loại thuê bao
            </span>
    </td>
    <td class="fr txt_value">
             <span id="order_sim_type_txt">
                <?= ($modelSim->type == WSim::TYPE_POSTPAID) ? Yii::t('web/portal', 'postpaid') : Yii::t('web/portal', 'prepaid') ?>
             </span>
    </td>
</tr>
<tr>
    <td class="fl">
            <span class="txt_label">
                Tên gói cước
            </span>
    </td>
    <td class="fr txt_value">
            <span id="order_package_name">
                <?= (isset($modelPackage->name)) ? CHtml::encode($modelPackage->name) : ''; ?>
            </span>
    </td>
</tr>

<?php if ($modelSim->type == WSim::TYPE_POSTPAID && isset($modelSim->price_term) && ($modelSim->price_term > 0)): ?>
    <tr>
        <td class="fl">
                <span class="txt_label">
                    Cước cam kết
                </span>
        </td>
        <td class="fr txt_value">
                <span>
                    <?= CHtml::encode(number_format($modelSim->price_term, 0, "", ".")); ?> đ/ tháng</span>
        </td>
    </tr>
<?php endif; ?>
<tr>
    <td>
        <div class="line"></div>
    </td>
</tr>
<tr>
    <td class="fl">
            <span class="txt_label">
                Giá sim
            </span>
    </td>
    <td class="fr txt_value">
            <span id="order_sim_price_txt">
                <?= CHtml::encode(number_format($modelSim->price, 0, "", ".")); ?>
            </span>đ
    </td>
</tr>
<?php if ($modelSim->type == WSim::TYPE_POSTPAID && isset($modelSim->price_term) && ($modelSim->price_term > 0)): ?>
    <tr>
        <td class="fl">
                <span class="txt_label">
                    Tiền đặt cọc
                </span>
        </td>
        <td class="fr txt_value">
                <span id="">
                    <?= CHtml::encode(number_format($modelSim->price_term, 0, "", ".")); ?>
                </span>đ
        </td>
    </tr>
<?php endif; ?>
<?php if ($modelSim->type == WSim::TYPE_PREPAID && isset($modelPackage->price)): ?>
    <tr>
        <td class="fl">
                <span class="txt_label">
                    Giá gói cước
                </span>
        </td>
        <td class="fr txt_value">
                <span id="order_package_price">
                    <?= isset($modelPackage->price) ? CHtml::encode(number_format($modelPackage->price, 0, "", ".")) . ' đ' : ''; ?>
                </span>
        </td>
    </tr>
<?php endif; ?>
<tr>
    <td class="fl">
            <span class="txt_label">
                Phí giao hàng:
            </span>
    </td>
    <td class="fr txt_value">
            <span id="order_ship_price_txt">
                <?= ($modelOrder->price_ship) ? (number_format($modelOrder->price_ship, 0, "", ".")) . ' đ' : 'Miễn phí'; ?>
            </span>
    </td>
</tr>

<tr>
    <td class="fl">
            <span class="txt_label">
                Giảm giá
            </span>
    </td>
    <td class="fr txt_value">
        <span>0 đ</span>
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