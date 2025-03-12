<?php
    /* @var $this CheckoutController */
    /* @var $arr_payment */
    /* @var $amount */
    /* @var $operation */
?>
<div class="panel">
    <?php if ($arr_payment['cod'] ||$arr_payment['qr_code'] || $arr_payment['vnpt_pay'] || $arr_payment['napas_atm']
        || $arr_payment['napas_int'] || $arr_payment['vietinbank']
        || $arr_payment['vietin_atm'] || $arr_payment['vnpay']): ?>
        <div class="group">
            <?php if ($arr_payment['qr_code']): ?>
                <div class="group">
                    <div class="radio select-method disabled">
                        <input type="radio" id="pm_<?= WPaymentMethod::PM_QR_CODE; ?>"
                               name="WOrders[payment_method]"
                               value="<?= WPaymentMethod::PM_QR_CODE; ?>">
                        <label for="pm_<?= WPaymentMethod::PM_QR_CODE; ?>">
                            <div class="payment-method">
                                <div class="col-md-2 col-xs-2 no_pad_xs">
                                    <div class="thumbnail">
                                        <img alt=""
                                             src="<?= Yii::app()->theme->baseUrl; ?>/images/pm_qrcode.png">
                                    </div>
                                </div>
                                <div class="col-md-10 col-xs-9 no_pad_right_xs">
                                    <div class="description">
                                        <div class="title uppercase">
                                            Thanh toán qua QR Code
                                        </div>
                                        <div class="subtitle">
                                            <p class="hidden-xs">
                                                Để thanh toán qua QR Code, quý khách phải đăng ký dịch vụ mobile banking
                                                của ngân hàng phát hành thẻ.
                                            </p>
                                            Xem thêm
                                            <a href="<?= Yii::app()->controller->createUrl('checkoutapi/guideQrCode'); ?>"
                                               target="_blank">
                                                <span class="txt_strong">"Hướng dẫn thanh toán qua QR Code"</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="space_10"></div>
            <?php endif; ?>
            <?php if ($arr_payment['vnpt_pay']): ?>
                <div class="group">
                    <div class="radio select-method disabled">
                        <input type="radio" id="pm_<?= WPaymentMethod::PM_VNPT_PAY; ?>"
                               name="WOrders[payment_method]"
                               value="<?= WPaymentMethod::PM_VNPT_PAY; ?>">
                        <label for="pm_<?= WPaymentMethod::PM_VNPT_PAY; ?>">
                            <div class="payment-method">
                                <div class="col-md-2 no_pad col-xs-2 no_pad_xs">
                                    <div class="thumbnail">
                                        <img alt=""
                                             src="<?= Yii::app()->theme->baseUrl; ?>/images/pm_vnpt_pay.png">
                                    </div>
                                </div>
                                <div class="col-md-10 col-xs-9 no_pad_right_xs">
                                    <div class="description">
                                        <div class="title uppercase">
                                            VNPT Pay
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="space_10"></div>
            <?php endif; ?>
            <?php if ($arr_payment['vietin_atm']): ?>
                <div class="group">
                    <div class="radio select-method disabled">
                        <input type="radio" id="pm_<?= WPaymentMethod::PM_VIETIN_ATM; ?>"
                               name="WOrders[payment_method]"
                               value="<?= WPaymentMethod::PM_VIETIN_ATM; ?>">
                        <label for="pm_<?= WPaymentMethod::PM_VIETIN_ATM; ?>">
                            <div class="payment-method">
                                <div class="col-md-2 no_pad col-xs-2 no_pad_xs">
                                    <div class="thumbnail">
                                        <img alt=""
                                             src="<?= Yii::app()->theme->baseUrl; ?>/images/pm_vietin_atm.png">
                                    </div>
                                </div>
                                <div class="col-md-10 col-xs-9 no_pad_right_xs">
                                    <div class="description">
                                        <div class="title uppercase">
                                            Thẻ nội địa Vietinbank
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="space_10"></div>
            <?php endif; ?>
            <?php if ($arr_payment['napas_atm']): ?>
                <div class="group">
                    <div class="radio select-method disabled">
                        <input type="radio" id="pm_<?= WPaymentMethod::PM_NAPAS_ATM; ?>"
                               name="WOrders[payment_method]"
                               value="<?= WPaymentMethod::PM_NAPAS_ATM; ?>">
                        <label for="pm_<?= WPaymentMethod::PM_NAPAS_ATM; ?>">
                            <div class="payment-method">
                                <div class="col-md-2 no_pad col-xs-2 no_pad_xs">
                                    <div class="thumbnail">
                                        <img alt=""
                                             src="<?= Yii::app()->theme->baseUrl; ?>/images/pm_napas_atm.png">
                                    </div>
                                </div>
                                <div class="col-md-10 col-xs-9 no_pad_right_xs">
                                    <div class="description">
                                        <div class="title uppercase">
                                            Thanh toán qua thẻ ATM nội địa
                                        </div>
                                        <div class="subtitle">
                                            <p class="hidden-xs">
                                                Để thực hiện thanh toán online bằng thẻ ATM
                                                nội địa, thẻ của Quý khách phải đăng ký dịch
                                                vụ Internet banking tại ngân hàng phát hành.
                                            </p>
                                            Xem thêm
                                            <a href="<?= Yii::app()->theme->baseUrl; ?>/document/payment_guide_napas.pdf"
                                               target="_blank">
                                                <span class="txt_strong">"Hướng dẫn thanh toán online bằng thẻ ATM nội địa"</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="space_10"></div>
            <?php endif; ?>
            <?php if ($arr_payment['vnpay']): ?>
                <div class="group">
                    <div class="radio select-method disabled">
                        <input type="radio" id="pm_<?= WPaymentMethod::PM_VNPAY; ?>"
                               name="WOrders[payment_method]"
                               value="<?= WPaymentMethod::PM_VNPAY; ?>">
                        <label for="pm_<?= WPaymentMethod::PM_VNPAY; ?>">
                            <div class="payment-method">
                                <div class="col-md-2 no_pad col-xs-2 no_pad_xs">
                                    <div class="thumbnail">
                                        <img alt=""
                                             src="<?= Yii::app()->theme->baseUrl; ?>/images/pm_vnpay.png">
                                    </div>
                                </div>
                                <div class="col-md-10 col-xs-9 no_pad_right_xs">
                                    <div class="description">
                                        <div class="title uppercase">
                                            VNPAY
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="space_10"></div>
            <?php endif; ?>
            <?php if ($arr_payment['vietinbank']): ?>
                <div class="group">
                    <div class="radio select-method disabled">
                        <input type="radio" id="pm_<?= WPaymentMethod::PM_VIETINBANK; ?>"
                               name="WOrders[payment_method]"
                               value="<?= WPaymentMethod::PM_VIETINBANK; ?>">
                        <label for="pm_<?= WPaymentMethod::PM_VIETINBANK; ?>">
                            <div class="payment-method">
                                <div class="col-md-2 no_pad col-xs-2 no_pad_xs">
                                    <div class="thumbnail">
                                        <img alt=""
                                             src="<?= Yii::app()->theme->baseUrl; ?>/images/pm_vietinbank.png">
                                    </div>
                                </div>
                                <div class="col-md-10 col-xs-9 no_pad_right_xs">
                                    <div class="description">
                                        <div class="title uppercase">
                                            Thanh toán qua thẻ quốc tế
                                        </div>
                                        <div class="subtitle">
                                            Xem thêm
                                            <a href="#"
                                               target="_blank">
                                                <span class="txt_strong">"Hướng dẫn thanh toán qua thẻ quốc tế"</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="space_10"></div>
            <?php endif; ?>
            <?php if ($arr_payment['napas_int']): ?>
                <div class="group">
                    <div class="radio select-method disabled">
                        <input type="radio" id="pm_<?= WPaymentMethod::PM_NAPAS_INT; ?>"
                               name="WOrders[payment_method]"
                               value="<?= WPaymentMethod::PM_NAPAS_INT; ?>">
                        <label for="pm_<?= WPaymentMethod::PM_NAPAS_INT; ?>">
                            <div class="payment-method">
                                <div class="col-md-2 no_pad col-xs-2 no_pad_xs">
                                    <div class="thumbnail">
                                        <img alt=""
                                             src="<?= Yii::app()->theme->baseUrl; ?>/images/pm_napas_int.png">
                                    </div>
                                </div>
                                <div class="col-md-10 col-xs-9 no_pad_right_xs">
                                    <div class="description">
                                        <div class="title uppercase">
                                            Thanh toán qua thẻ quốc tế Napas
                                        </div>
                                        <div class="subtitle">
                                            <p class="hidden-xs">
                                                Để thực hiện thanh toán online bằng thẻ quốc tế,
                                                thẻ của Quý khách phải đăng ký chức năng thanh
                                                toán online tại ngân hàng phát hành.
                                            </p>
                                            Xem thêm
                                            <a href="<?= Yii::app()->theme->baseUrl; ?>/document/payment_guide_napas.pdf"
                                               target="_blank">
                                                <span class="txt_strong">"Hướng dẫn thanh toán online bằng thẻ quốc tế VISA, MASTER"</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="space_10"></div>
            <?php endif; ?>
            <?php if ($arr_payment['cod']): ?>
                <div class="group">
                    <div class="radio select-method disabled">
                        <input type="radio" id="pm_<?= WPaymentMethod::PM_COD; ?>" name="WOrders[payment_method]"
                               value="<?= WPaymentMethod::PM_COD; ?>">
                        <label for="pm_<?= WPaymentMethod::PM_COD; ?>">
                            <div class="payment-method">
                                <div class="col-md-2 col-xs-2 no_pad_xs">
                                    <div class="thumbnail">
                                        <img alt="" src="<?= Yii::app()->theme->baseUrl; ?>/images/pm_cod.png">
                                    </div>
                                </div>
                                <div class="col-md-10 col-xs-9 no_pad_right_xs">
                                    <div class="description">
                                        <div class="title uppercase">
                                            Thanh toán khi nhận hàng
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="space_10"></div>
            <?php endif; ?>
            <?php if (!empty($operation) && $operation == OrdersData::OPERATION_BUYSIM): ?>
                <div class="col-md-12 col-xs-12 no_pad_right_xs">
                    <p class="font_15 lbl_color_blue">
                        Chú ý với phương thức thanh toán online:
                    </p>
                    <p class="font_12">
                        Số tiền thanh toán Online: <span
                                class="lbl_color_blue"><?= number_format($amount, 0, "", ".") ?>đ</span>
                    </p>
                    <?php if (!empty($lbl_price_ship)): ?>
                        <p class="font_12">
                            Phí giao hàng (thanh toán khi nhận hàng): <span
                                    class="lbl_color_blue"><?= $lbl_price_ship; ?></span>
                        </p>
                    <?php endif; ?>
                </div>

            <?php endif; ?>
        </div>
        <div class="space_10"></div>
    <?php endif; ?>
</div>