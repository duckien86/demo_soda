<?php
    /* @var $this PackageController */
    /* @var $form CActiveForm */
?>

<div class="font_bold">Gói cước theo ngày thời hạn sử dụng 24h tính từ thời điểm đăng ký thành công</div>
<div class="space_30"></div>
<?php $day_call_int = WPackage::getListPackageByType(WPackage::PACKAGE_CALL_INT, '', FALSE, NULL, WPackage::PERIOD_1);
    if ($day_call_int):
        ?>
        <div class="form-group">
            <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_call.png" alt="" class="icon">Thoại nội mạng
            </div>
            <div id="pack_call_int" class="bs-wizard">
                <label class="col-md-2 bs-wizard-step complete">
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <span id="package1" class="bs-wizard-dot">
                        <input class="chk_package" id="WOrders_package_1_0" value="0" type="checkbox"
                               name="WOrders[package][]">
                    </span>
                    <div class="bs-wizard-info text-center" for="WOrders_package_1_0">
                        <div>0 phút</div>
                        <div>0đ</div>
                    </div>
                </label>
                <?php foreach ($day_call_int as $key => $item): ?>
                    <label class="col-md-2 bs-wizard-step complete">
                        <div class="progress">
                            <div class="progress-bar"></div>
                        </div>
                        <span id="package1" class="bs-wizard-dot">
                            <input class="chk_package" id="WOrders_package_1_<?= CHtml::encode($item['code']) ?>"
                                   value="<?= CHtml::encode($item['code']) ?>"
                                   type="checkbox" name="WOrders[package][]">
                        </span>
                        <div class="bs-wizard-info text-center"
                             for="WOrders_package_1_<?= CHtml::encode($item['code']) ?>">
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
<?php $day_call_ext = WPackage::getListPackageByType(WPackage::PACKAGE_CALL_EXT, '', FALSE, NULL, WPackage::PERIOD_1);
    if ($day_call_ext):
        ?>
        <div class="space_10"></div>
        <div class="form-group">
            <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_call.png" alt="" class="icon">Thoại ngoại mạng
            </div>
            <div id="pack_call_ext" class="row bs-wizard">
                <label class="col-md-2 bs-wizard-step complete">
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <span id="package2" class="bs-wizard-dot">
                        <input class="chk_package" id="WOrders_package_2_0" value="0" type="checkbox"
                               name="WOrders[package][]">
                    </span>
                    <div class="bs-wizard-info text-center" for="WOrders_package_2_0">
                        <div>0 phút</div>
                        <div>0đ</div>
                    </div>
                </label>
                <?php foreach ($day_call_ext as $key => $item):
                    ?>
                    <label class="col-md-2 bs-wizard-step complete">
                        <div class="progress">
                            <div class="progress-bar"></div>
                        </div>
                        <span id="package2" class="bs-wizard-dot">
                            <input class="chk_package" id="WOrders_package_2_<?= CHtml::encode($item['code']) ?>"
                                   value="<?= CHtml::encode($item['code']) ?>"
                                   type="checkbox" name="WOrders[package][]">
                        </span>
                        <div class="bs-wizard-info text-center"
                             for="WOrders_package_2_<?= CHtml::encode($item['code']) ?>">
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
<?php $day_sms_int = WPackage::getListPackageByType(WPackage::PACKAGE_SMS_INT, '', FALSE, NULL, WPackage::PERIOD_1);
    if ($day_sms_int):
        ?>
        <div class="space_10"></div>
        <div class="form-group">
            <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_sms.png" alt="" class="icon">SMS nội mạng
            </div>
            <div id="pack_sms_int" class="row bs-wizard">
                <label class="col-md-2 bs-wizard-step complete">
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <span id="package3" class="bs-wizard-dot">
                        <input class="chk_package" id="WOrders_package_3_0" value="0" type="checkbox"
                               name="WOrders[package][]">
                    </span>
                    <div class="bs-wizard-info text-center" for="WOrders_package_3_0">
                        <div>0 SMS</div>
                        <div>0đ</div>
                    </div>
                </label>
                <?php foreach ($day_sms_int as $key => $item): ?>
                    <label class="col-md-2 bs-wizard-step complete">
                        <div class="progress">
                            <div class="progress-bar"></div>
                        </div>
                        <span id="package3" class="bs-wizard-dot">
                            <input class="chk_package" id="WOrders_package_3_<?= CHtml::encode($item['code']) ?>"
                                   value="<?= CHtml::encode($item['code']) ?>"
                                   type="checkbox" name="WOrders[package][]">
                        </span>
                        <div class="bs-wizard-info text-center"
                             for="WOrders_package_3_<?= CHtml::encode($item['code']) ?>">
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
<?php $day_sms_ext = WPackage::getListPackageByType(WPackage::PACKAGE_SMS_EXT, '', FALSE, NULL, WPackage::PERIOD_1);
    if ($day_sms_ext):
        ?>
        <div class="space_10"></div>
        <div class="form-group">
            <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_sms.png" alt="" class="icon">SMS ngoại mạng
            </div>
            <div id="pack_sms_ext" class="row bs-wizard">

                <label class="col-md-2 bs-wizard-step complete">
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <span id="package4" class="bs-wizard-dot">
                        <input class="chk_package" id="WOrders_package_4_0" value="0" type="checkbox"
                               name="WOrders[package][]">
                    </span>
                    <div class="bs-wizard-info text-center" for="WOrders_package_4_0">
                        <div>0 SMS</div>
                        <div>0đ</div>
                    </div>
                </label>
                <?php foreach ($day_sms_ext as $key => $item): ?>
                    <label class="col-md-2 bs-wizard-step complete">
                        <div class="progress">
                            <div class="progress-bar"></div>
                        </div>
                        <span id="package4" class="bs-wizard-dot">
                            <input class="chk_package" id="WOrders_package_4_<?= CHtml::encode($item['code']) ?>"
                                   value="<?= CHtml::encode($item['code']) ?>"
                                   type="checkbox" name="WOrders[package][]">
                        </span>
                        <div class="bs-wizard-info text-center"
                             for="WOrders_package_4_<?= CHtml::encode($item['code']) ?>">
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
<?php $day_data = WPackage::getListPackageByType(WPackage::PACKAGE_DATA_FLEX, '', FALSE, NULL, WPackage::PERIOD_1);
    if ($day_data):
        ?>
        <div class="space_10"></div>
        <div class="form-group">
            <div><img src="<?= Yii::app()->theme->baseUrl; ?>/images/icon_data.png" alt="" class="icon">Data</div>
            <div id="pack_data" class="row bs-wizard">
                <label class="col-md-2 bs-wizard-step complete">
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <span id="package5" class="bs-wizard-dot">
                        <input class="chk_package" id="WOrders_package_5_0" value="0" type="checkbox"
                               name="WOrders[package][]">
                    </span>
                    <div class="bs-wizard-info text-center" for="WOrders_package_5_0">
                        <div>0 MB</div>
                        <div>0đ</div>
                    </div>
                </label>
                <?php foreach ($day_data as $key => $item): ?>
                    <label class="col-md-2 bs-wizard-step complete">
                        <div class="progress">
                            <div class="progress-bar"></div>
                        </div>
                        <span id="package5" class="bs-wizard-dot">
                            <input class="chk_package" id="WOrders_package_5_<?= CHtml::encode($item['code']) ?>"
                                   value="<?= CHtml::encode($item['code']) ?>"
                                   type="checkbox" name="WOrders[package][]">
                        </span>
                        <div class="bs-wizard-info text-center"
                             for="WOrders_package_5_<?= CHtml::encode($item['code']) ?>">
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
    <p class="font_bold">Thời gian sử dụng: 24h00 tính từ thời điểm đăng ký thành công</p>
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
        <li><p>Gia hạn: gia hạn tự động</p></li>
    </ul>
    <p class="font_bold">Tra cứu</p>
    <ul class="disc">
        <li><p>Lưu lượng thoại/sms: TRACUU gửi 900</p></li>
        <li><p>Tra cứu data: DATA gửi 888</p></li>
    </ul>
</div>