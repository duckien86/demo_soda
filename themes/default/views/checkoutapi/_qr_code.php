<?php
    /* @var $modelOrder WOrders */
    /* @var $amount */
    /* @var $qr_code */

    $appId  = Yii::app()->params->intent_appid;
    $packId = Yii::app()->params->intent_packid;

    $detect = new MyMobileDetect();
    if ($detect->isiOS()) {
        $open_app_link = "$appId://$qr_code";
    } else if ($detect->isAndroidOS()) {
        $open_app_link = "intent://view?data=$qr_code#Intent;scheme=$appId;package=$packId;end";
    }
    $lbl_price_ship = 'miễn phí';
    if ($modelOrder->delivery_type == WOrders::DELIVERY_TYPE_HOME) {
        $amount         -= $GLOBALS['config_common']['order']['price_ship'];
        $lbl_price_ship = number_format($GLOBALS['config_common']['order']['price_ship'], 0, "", ".") . 'đ';
    }
?>
<script src="<?= Yii::app()->theme->baseUrl; ?>/js/jquery.qrcode.min.js"></script>
<div class="text-center">
    <p class="uppercase lbl_color_blue font_20">Thanh toán qua ứng dụng</p>
    <p class="lbl_color_blue font_20">Mobile Banking</p>
</div>
<div class="text-center">
    <?php if ($detect->isMobile()): ?>
        <p class="uppercase lbl_color_pink font_20">Chạm vào mã QR Code để thanh toán</p>
    <?php else: ?>
        <p class="uppercase lbl_color_pink font_20">Sử dụng ứng dụng của ngân hàng để quét mã</p>
    <?php endif; ?>
</div>
<div class="space_10"></div>
<a href="<?= $open_app_link ?>">
    <div id="qrcodeCanvas"></div>
    <div class="space_20"></div>
    <div class="text-center">
        <p class="amount lbl_color_pink lbl_bold">
            Số tiền thanh toán qua QR Code: <?= number_format($amount, 0, "", ".") ?>đ
        </p>
        <p class="amount lbl_color_pink lbl_bold">
            Phí giao hàng (thanh toán khi nhận hàng): <?= $lbl_price_ship; ?>
        </p>
    </div>
    <div class="space_10"></div>
    <div class="text-center">
        <a href="<?= Yii::app()->controller->createUrl('checkoutapi/guideQrCode'); ?>" target="_blank"
           class="lbl_color_pink font_15">
            Hướng dẫn thanh toán
        </a>
    </div>
    <div class="space_10"></div>
    <div class="text-center">
        <p class="lbl_color_blue font_15">
            Hệ thống sẽ tự động hoàn tất đơn hàng khi bạn thực hiện thanh toán thành công.
        </p>
    </div>
</a>
<script>
    $('#qrcodeCanvas').qrcode({
        width: 200, height: 200,
        text: '<?= $qr_code?>'
    });

    // cancel all js process
    $("a").click(function (event) {
        window.stop();
    });
</script>
