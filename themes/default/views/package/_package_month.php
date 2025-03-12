<?php
    /* @var $this PackageController */
    /* @var $form CActiveForm */
?>

<div class="font_bold">Gói cước theo tháng thời hạn sử dụng 30 ngày tính từ ngày đăng ký gói</div>
<div class="space_30"></div>
<?php $month_call_int = WPackage::getListPackageByType(WPackage::PACKAGE_CALL_INT, '', FALSE, NULL, WPackage::PERIOD_30);
    if ($month_call_int):
        ?>
        <div class="form-group">
            <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_call.png" alt="" class="icon">Thoại nội mạng
            </div>
            <div id="pack_call_int_month" class="row bs-wizard">
                <label class="col-md-2 bs-wizard-step complete">
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <span id="package6" class="bs-wizard-dot">
                        <input class="chk_package" id="WOrders_package_6_0" value="0" type="checkbox"
                               name="WOrders[package][]">
                    </span>
                    <div class="bs-wizard-info text-center" for="WOrders_package_6_0">
                        <div>0 phút</div>
                        <div>0đ</div>
                    </div>
                </label>
                <?php foreach ($month_call_int as $key => $item): ?>
                    <label class="col-md-2 bs-wizard-step complete">
                        <div class="progress">
                            <div class="progress-bar"></div>
                        </div>
                        <span id="package6" class="bs-wizard-dot">
                            <input class="chk_package" id="WOrders_package_6_<?= CHtml::encode($item['code']) ?>"
                                   value="<?= CHtml::encode($item['code']) ?>"
                                   type="checkbox" name="WOrders[package][]">
                        </span>
                        <div class="bs-wizard-info text-center"
                             for="WOrders_package_6_<?= CHtml::encode($item['code']) ?>">
                            <div>
                                <?= CHtml::encode($item['short_description']); ?>
                            </div>
                            <div>
                                <?= number_format(CHtml::encode($item['price']), 0, "", ".") . 'đ'; ?>
                            </div>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
<?php $month_call_ext = WPackage::getListPackageByType(WPackage::PACKAGE_CALL_EXT, '', FALSE, NULL, WPackage::PERIOD_30);
    if ($month_call_ext):
        ?>
        <div class="space_10"></div>
        <div class="form-group">
            <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_call.png" alt="" class="icon">Thoại ngoại mạng
            </div>
            <div id="pack_call_ext_month" class="row bs-wizard">
                <label class="col-md-2 bs-wizard-step complete">
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <span id="package7" class="bs-wizard-dot">
                        <input class="chk_package" id="WOrders_package_7_0" value="0" type="checkbox"
                               name="WOrders[package][]">
                    </span>
                    <div class="bs-wizard-info text-center" for="WOrders_package_7_0">
                        <div>0 phút</div>
                        <div>0đ</div>
                    </div>
                </label>
                <?php foreach ($month_call_ext as $key => $item): ?>
                    <label class="col-md-2 bs-wizard-step complete">
                        <div class="progress">
                            <div class="progress-bar"></div>
                        </div>
                        <span id="package7" class="bs-wizard-dot">
                            <input class="chk_package" id="WOrders_package_7_<?= CHtml::encode($item['code']) ?>"
                                   value="<?= CHtml::encode($item['code']) ?>"
                                   type="checkbox" name="WOrders[package][]">
                        </span>
                        <div class="bs-wizard-info text-center"
                             for="WOrders_package_7_<?= CHtml::encode($item['code']) ?>">
                            <div>
                                <?= CHtml::encode($item['short_description']); ?>
                            </div>
                            <div>
                                <?= number_format(CHtml::encode($item['price']), 0, "", ".") . 'đ'; ?>
                            </div>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
<?php $month_sms_int = WPackage::getListPackageByType(WPackage::PACKAGE_SMS_INT, '', FALSE, NULL, WPackage::PERIOD_30);
    if ($month_sms_int):
        ?>
        <div class="space_10"></div>
        <div class="form-group">
            <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_sms.png" alt="" class="icon">SMS nội mạng
            </div>
            <div id="pack_sms_int_month" class="row bs-wizard">
                <label class="col-md-2 bs-wizard-step complete">
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <span id="package8" class="bs-wizard-dot">
                        <input class="chk_package" id="WOrders_package_8_0" value="0" type="checkbox" name="WOrders[package][]">
                    </span>
                    <div class="bs-wizard-info text-center" for="WOrders_package_8_0">
                        <div>0 SMS</div>
                        <div>0đ</div>
                    </div>
                </label>
                <?php foreach ($month_sms_int as $key => $item): ?>
                    <label class="col-md-2 bs-wizard-step complete">
                        <div class="progress">
                            <div class="progress-bar"></div>
                        </div>
                        <span id="package8" class="bs-wizard-dot">
                            <input class="chk_package" id="WOrders_package_8_<?= CHtml::encode($item['code']) ?>"
                                   value="<?= CHtml::encode($item['code']) ?>"
                                   type="checkbox" name="WOrders[package][]">
                        </span>
                        <div class="bs-wizard-info text-center"
                             for="WOrders_package_8_<?= CHtml::encode($item['code']) ?>">
                            <div>
                                <?= CHtml::encode($item['short_description']); ?>
                            </div>
                            <div>
                                <?= number_format(CHtml::encode($item['price']), 0, "", ".") . 'đ'; ?>
                            </div>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
<?php $month_sms_ext = WPackage::getListPackageByType(WPackage::PACKAGE_SMS_EXT, '', FALSE, NULL, WPackage::PERIOD_30);
    if ($month_sms_ext):
        ?>
        <div class="space_10"></div>
        <div class="form-group">
            <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_sms.png" alt="" class="icon">SMS ngoại mạng
            </div>
            <div id="pack_sms_ext_month" class="row bs-wizard">
                <label class="col-md-2 bs-wizard-step complete">
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <span id="package9" class="bs-wizard-dot">
                        <input class="chk_package" id="WOrders_package_9_0" value="0" type="checkbox" name="WOrders[package][]">
                    </span>
                    <div class="bs-wizard-info text-center" for="WOrders_package_9_0">
                        <div>0 SMS</div>
                        <div>0đ</div>
                    </div>
                </label>
                <?php foreach ($month_sms_ext as $key => $item): ?>
                    <label class="col-md-2 bs-wizard-step complete">
                        <div class="progress">
                            <div class="progress-bar"></div>
                        </div>
                        <span id="package9" class="bs-wizard-dot">
                            <input class="chk_package" id="WOrders_package_9_<?= CHtml::encode($item['code']) ?>"
                                   value="<?= CHtml::encode($item['code']) ?>"
                                   type="checkbox" name="WOrders[package][]">
                        </span>
                        <div class="bs-wizard-info text-center"
                             for="WOrders_package_9_<?= CHtml::encode($item['code']) ?>">
                            <div>
                                <?= CHtml::encode($item['short_description']); ?>
                            </div>
                            <div>
                                <?= number_format(CHtml::encode($item['price']), 0, "", ".") . 'đ'; ?>
                            </div>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
<?php $month_data = WPackage::getListPackageByType(WPackage::PACKAGE_DATA_FLEX, '', FALSE, NULL, WPackage::PERIOD_30);
    if ($month_data):
        ?>
        <div class="space_10"></div>
        <div class="form-group">
            <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_data.png" alt="" class="icon">Data</div>
            <div id="pack_data_month" class="row bs-wizard">
                <label class="col-md-2 bs-wizard-step complete">
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <span id="package10" class="bs-wizard-dot">
                        <input class="chk_package" id="WOrders_package_10_0" value="0" type="checkbox"
                               name="WOrders[package][]">
                    </span>
                    <div class="bs-wizard-info text-center" for="WOrders_package_10_0">
                        <div>0 SMS</div>
                        <div>0đ</div>
                    </div>
                </label>
                <?php foreach ($month_data as $key => $item): ?>
                    <label class="col-md-2 bs-wizard-step complete">
                        <div class="progress">
                            <div class="progress-bar"></div>
                        </div>
                        <span id="package10" class="bs-wizard-dot">
                            <input class="chk_package" id="WOrders_package_10_<?= CHtml::encode($item['code']) ?>"
                                   value="<?= CHtml::encode($item['code']) ?>"
                                   type="checkbox" name="WOrders[package][]">
                        </span>
                        <div class="bs-wizard-info text-center"
                             for="WOrders_package_10_<?= CHtml::encode($item['code']) ?>">
                            <div>
                                <?= CHtml::encode($item['short_description']); ?>
                            </div>
                            <div>
                                <?= number_format(CHtml::encode($item['price']), 0, "", ".") . 'đ'; ?>
                            </div>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
<div class="form-group fl package_note static_page">
    <p class="font_bold">Điều kiện sử dụng:</p>
    <ul class="disc">
        <li><p>Thuê bao đang không sử dụng bất kỳ gói cước trả trước nào.</p></li>
        <li><p>Thuê bao phải còn Tài khoản chính tối thiểu theo quy định đủ để gọi, nhắn tin, truy cập GPRS và còn thời hạn sử
                dụng.</p></li>
    </ul>
    <p class="font_bold">Thời gian sử dụng: 30 ngày kể từ thời điểm kích hoạt thành công</p>
    <p class="font_bold">Quy định về sử dụng gói cước:</p>
    <ul class="disc">
        <li>
            <p>Đăng ký gói cước:</p>
            <ul class="circle">
                <li><p>Qua Web</p></li>
            </ul>
        </li>
        <li>
            <p>Hủy gói cước:</p>
            <ul class="circle">
                <li><p>Qua Web</p></li>
                <li><p>SMS</p></li>
            </ul>
        </li>
        <li><p>Gia hạn: tự động gia hạn</p></li>
    </ul>
    <p class="font_bold">Tra cứu</p>
    <ul class="disc">
        <li><p>Lưu lượng thoại/sms: TRACUU gửi 900</p></li>
        <li><p>Tra cứu data: DATA gửi 888</p></li>
    </ul>
</div>