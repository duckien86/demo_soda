<?php
    /* @var $this PackageController */
    /* @var $modelOrder WOrders */
    /* @var $modelPackage WPackage */
    /* @var $package_flexible */
?>
<tr class="border_bottom">
    <td class="fl">
        <span class="txt_label">
           Số thuê bao
        </span>
    </td>
    <td class="fr txt_value">
        <span>
            <?= CHtml::encode($modelOrder->phone_contact); ?>
        </span>
    </td>
</tr>
<?php if (isset($package_flexible)):
    foreach ($package_flexible as $item):
        ?>
        <tr class="border_bottom">
            <td class="fl">
                <span class="txt_label">
                    <?= $modelPackage->getPackageTypeLabel($item->type, TRUE) ?>
                </span>
            </td>
            <td class="fr txt_value">
                <span>
                    <?= $item->short_description . '/' . number_format(($item->price), 0, "", ".") . 'đ' ?>
                </span>
            </td>
        </tr>
        <?php
    endforeach;
endif; ?>
<tr>
    <td class="fl lbl_amount">
        <span class="txt_label">
            Tổng giá trị đơn hàng
        </span>
    </td>
    <td class="fr txt_value col_amount">
        <span id="order_total_amount">
            <?= CHtml::encode(number_format(($modelOrder->amount), 0, "", ".")) . 'đ'; ?>
        </span>
    </td>
</tr>
<tr>
    <td class="note">
        <?php
            if ($modelPackage->period == WPackage::PERIOD_1):?>
                <p>*Thời hạn sử dụng 24h00 tính từ thời điểm đăng ký thành công</p>
            <?php elseif ($modelPackage->period == WPackage::PERIOD_30): ?>
                <p>*Thời hạn sử dụng 30 ngày kể từ thời điểm đăng ký thành công</p>
            <?php endif; ?>
    </td>
</tr>