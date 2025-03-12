<?php
$detect = new MyMobileDetect();
$isMobile = $detect->isMobile();
?>
<!--<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css"/>-->
<div class="container mar-top-bottom-20">
    <div class="row">
        <div class="col-lg-8">
            <div class="form-reg-fiber pad-20">
                <div class="alert alert-success"
                     style="background: #ed0677!important; color: #fff !important; border-radius: 0px !important;padding: 10px;border: 1px #ed0677 solid;">
                    <strong>Thông tin đăng ký gói cước</strong>
                </div>
                <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'form_register_fiber',
                    'action' => Yii::app()->controller->createUrl('package/registerfibers', array('package' => $modelPackage->id)),
                    'enableAjaxValidation' => FALSE,
                    'enableClientValidation' => FALSE,
                )); ?>
                <div class="row">
                    <div class="col-lg-12">
                        <span style="font-weight: bold; margin-left: 40px; font-size: 15px; margin-bottom: 20px">Thông tin liên hệ lắp đặt dịch vụ </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">

                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <?php if (isset($mes)) { ?>
                                    <div class="mes-api" style="color: red">
                                        <?php echo $mes; ?>
                                    </div>
                                <?php } ?>
                                <!--                                --><?php //echo CHtml::errorSummary($modelRegFiber); ?>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <label>Tên khách hàng <span style="color: red">(*)</span></label>
                                <?php echo $form->textField($modelRegFiber, 'ten_kh', array('class' => 'form-control', 'maxlength' => 255, 'placeholder' => 'Họ và tên khách hàng lắp đặt', 'onchange' => 'set_value_fullname_preview()')); ?>
                                <?php echo $form->error($modelRegFiber, 'ten_kh'); ?>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <label>Số điện thoại khách hàng <span style="color: red">(*)</span></label>
                                <?php echo $form->textField($modelRegFiber, 'so_dt', array('class' => 'form-control', 'maxlength' => 255, 'placeholder' => 'Điện thoại khách hàng lắp đặt', 'onchange' => 'set_value_phone_preview()')); ?>
                                <?php echo $form->error($modelRegFiber, 'so_dt'); ?>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <?php if (isset($list_province)) { ?>
                                    <label>Tỉnh/TP <span style="color: red">(*)</span></label>
                                    <?php
                                    $this->widget(
                                        'booster.widgets.TbSelect2',
                                        array(
                                            'model' => $modelRegFiber,
                                            'attribute' => 'tinh_id',
                                            'data' => $list_province,
                                            'htmlOptions' => array(
                                                'multiple' => FALSE,
                                                'prompt' => Yii::t('web/portal', 'select_province'),
                                                'ajax' => array(
                                                    'type' => 'POST',

                                                    'url' => Yii::app()->controller->createUrl('package/getDistrictByProvince'), //or $this->createUrl('loadcities') if '$this' extends CController

                                                    'update' => '#WRegFiber_quan_id', //or 'success' => 'function(data){...handle the data in the way you want...}',
                                                    'data' => array('tinh_id' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                                ),
                                                'onchange' => '$("#WRegFiber_quan_id").select2("val", "");
                                        $("#WOrders_ward_code").select2("val", "");
                                        $("#WOrders_brand_offices").select2("val", "");
                                        $("#brand_offices_info").html("");
                                    '//reset value selected
//                        'style'=>'width:100%'
                                            ),
                                        )
                                    );
                                    ?>
                                    <?php echo $form->error($modelRegFiber, 'tinh_id'); ?>
                                <?php } ?>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Quận/Huyện <span style="color: red">(*)</span></label>
                                        <?php
                                        $this->widget(
                                            'booster.widgets.TbSelect2',
                                            array(
                                                'model' => $modelRegFiber,
                                                'attribute' => 'quan_id',
                                                'data' => $district,
                                                'htmlOptions' => array(
                                                    'multiple' => FALSE,
                                                    'prompt' => Yii::t('web/portal', 'select_district'),
                                                    'ajax' => array(
                                                        'type' => 'POST',
                                                        'dataType' => 'json',
                                                        'url' => Yii::app()->controller->createUrl('package/getWardBrandOfficesByDistrict'),
                                                        'success' => 'function(data){
                                                            $("#WRegFiber_phuong_id").html(data.html_ward);
                                                        }',
                                                        'data' => array('tinh_id' => '$("#WRegFiber_tinh_id").select2("val", "")', 'quan_id' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                                    ),
                                                    'onchange' => '$("#WRegFiber_phuong_id").select2("val", "");'
                                                ),
                                            )
                                        );
                                        ?>
                                        <?php echo $form->error($modelRegFiber, 'quan_id'); ?>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Phường/Xã <span style="color: red">(*)</span></label>
                                        <?php
                                        $this->widget(
                                            'booster.widgets.TbSelect2',
                                            array(
                                                'model' => $modelRegFiber,
                                                'attribute' => 'phuong_id',
                                                'data' => $convert_ward_data,
                                                'htmlOptions' => array(
                                                    'multiple' => FALSE,
                                                    'prompt' => Yii::t('web/portal', 'select_ward'),
                                                    'ajax' => array(
                                                        'type' => 'POST',
                                                        'dataType' => 'json',
//                                                        'url' => Yii::app()->controller->createUrl('package/getStreetFiber'),
//                                                        'success' => 'function(data){
//                                                            $("#WRegFiber_pho_id").html(data.html_street);
//                                                        }',
                                                        'data' => array('phuong_id' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                                    ),
                                                    'onchange' => '$("#WRegFiber_pho_id").select2("val", "");'
                                                ),
                                            )
                                        );
                                        ?>
                                        <?php echo $form->error($modelRegFiber, 'phuong_id'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                        <div class="row custom-row">
                            <div class="col-lg-2"></div>
                            <div class="col-12 col-xs-12 col-md-6col-sm-6 col-lg-6">
                                <div class="wrap-radio">
                                    <label class="radio-inline"><input onclick="getPho()" value="1" type="checkbox"
                                                                       id="WRegFiber_chose_street"
                                                                       name="WRegFiber[chose_street]">Phố</label>
                                    <label class="radio-inline"><input onclick="getAp()" value="2" type="checkbox"
                                                                       id="WRegFiber_chose_street1"
                                                                       name="WRegFiber[chose_street]">Ấp</label>
                                    <label class="radio-inline"><input onclick="getKhu()" value="3" type="checkbox"
                                                                       id="WRegFiber_chose_street2"
                                                                       name="WRegFiber[chose_street]">Khu</label>
                                </div>
                                <div class="wrap-radio">
                                    <?php echo $form->error($modelRegFiber, 'chose_street'); ?>
                                </div>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                        <div class="row row-pho" id="pho">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php
                                        $this->widget(
                                            'booster.widgets.TbSelect2',
                                            array(
                                                'model' => $modelRegFiber,
                                                'attribute' => 'pho_id',
                                                'data' => $street,
                                                'htmlOptions' => array(
                                                    'multiple' => FALSE,
                                                    'prompt' => Yii::t('web/portal', 'select_street'),
                                                ),

                                            )
                                        );
                                        ?>
                                        <?php echo $form->error($modelRegFiber, 'pho_id'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-pho" id="ap">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php
                                        $this->widget(
                                            'booster.widgets.TbSelect2',
                                            array(
                                                'model' => $modelRegFiber,
                                                'attribute' => 'ap_id',
                                                'data' => $street,
                                                'htmlOptions' => array(
                                                    'multiple' => FALSE,
                                                    'prompt' => Yii::t('web/portal', 'select_street1'),
                                                ),

                                            )
                                        );
                                        ?>
                                        <?php echo $form->error($modelRegFiber, 'ap_id'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-pho" id="khu">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php
                                        $this->widget(
                                            'booster.widgets.TbSelect2',
                                            array(
                                                'model' => $modelRegFiber,
                                                'attribute' => 'khu_id',
                                                'data' => $street,
                                                'htmlOptions' => array(
                                                    'multiple' => FALSE,
                                                    'prompt' => Yii::t('web/portal', 'select_street2'),
                                                ),

                                            )
                                        );
                                        ?>
                                        <?php echo $form->error($modelRegFiber, 'khu_id'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Số nhà <span style="color: red">(*)</span></label>
                                        <?php echo $form->textField($modelRegFiber, 'so_nha', array('class' => 'form-control', 'maxlength' => 255, 'placeholder' => 'Địa chỉ số nhà', 'onclick' => 'addressdetail();')); ?>
                                        <?php echo $form->error($modelRegFiber, 'so_nha'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <input type="hidden" value="58" id="WRegFiber_loaitb_id" name="WRegFiber[loaitb_id]">
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <input type="hidden" value="2" id="WRegFiber_loai" name="WRegFiber[loai]">
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <label>Mã khuyến mại / Mã CTV</label>
                                <?php echo $form->textField($modelRegFiber, 'promo_code', array('class' => 'form-control', 'maxlength' => 255, 'placeholder' => 'Mã CTV / Mã khuyến mại (Nếu có)')); ?>
                                <?php echo $form->error($modelRegFiber, 'promo_code'); ?>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" style="margin-bottom: 20px !important; margin-top: 20px !important;">
                        <span style="font-weight: bold; margin-left: 40px; font-size: 15px; margin-bottom: 20px">Thông tin người yêu cầu/giới thiệu (nếu có) </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <label>Tên người yêu cầu</label>
                                <?php echo $form->textField($modelRegFiber, 'ten_yc', array('class' => 'form-control', 'maxlength' => 255, 'placeholder' => 'Họ và tên người yêu cầu/giới thiệu')); ?>
                                <?php echo $form->error($modelRegFiber, 'ten_yc'); ?>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <label>Số điện thoại người yêu cầu</label>
                                <?php echo $form->textField($modelRegFiber, 'so_dt_yc', array('class' => 'form-control', 'maxlength' => 255, 'placeholder' => 'Điện thoại người yêu cầu/giới thiệu')); ?>
                                <?php echo $form->error($modelRegFiber, 'so_dt_yc'); ?>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" style="text-align: center">
                        <?php if (!$isMobile) { ?>
                            <button class="btn btn-register"
                                    style="background: #ed0677 !important; color: #fff; margin-top: 20px">Đăng ký
                            </button>
                        <?php } else { ?>
                            <a class="btn btn-register"
                               style="background: #ed0677 !important; color: #fff; margin-top: 20px" data-toggle="modal"
                               data-target="#previeworder">Đăng ký
                            </a>
                            <!-- Modal -->
                            <div id="previeworder" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Thông tin đơn hàng</h4>
                                        </div>
                                        <div class="modal-body content-order-fiber-mobile">
                                            <div class="w100">
                                                <div class="w50">Tên gói :</div>
                                                <div class="w50"><?php echo $modelPackage->name ?></div>
                                            </div>
                                            <div class="w100">
                                                <div class="w50">Chu kỳ :</div>
                                                <div class="w50"><?php echo WPackage::model()->getPackagePeriodLabel($modelPackage->period) ?></div>
                                            </div>
                                            <div class="w100">
                                                <div class="w50">Giá gói :</div>
                                                <div class="w50"><?php echo number_format($modelPackage->price) ?>VNĐ
                                                </div>
                                            </div>
                                            <div class="w100">
                                                <div class="w50">Họ tên :</div>
                                                <div class="w50">
                                                    <div id="fullnamepreview"></div>
                                                </div>
                                            </div>
                                            <div class="w100">
                                                <div class="w50">Số điện thoại :</div>
                                                <div class="w50">
                                                    <div id="phonepreview"></div>
                                                </div>
                                            </div>
                                            <div class="w100">
                                                <div class="w50">Phương thức thanh toán :</div>
                                                <div class="w50">
                                                    <div id="">COD</div>
                                                </div>
                                            </div>
                                            <div class="w100"
                                                 style="<?php if ($modelPackage->commercial && $modelPackage->commercial !== '') {
                                                     echo 'color:red';
                                                 } ?>">
                                                <div class="w50">Khuyến mại :</div>
                                                <div class="w50">
                                                    <div>
                                                        <?php if (!$modelPackage->commercial || $modelPackage->commercial === '') { ?>
                                                            <?php echo 'Không áp dụng'; ?>
                                                        <?php } else { ?>
                                                            <?php echo $modelPackage->commercial ?>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer modal-footer-mobile">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Thoát
                                            </button>
                                            <button class="btn btn-register"
                                                    style="background: #ed0677 !important; color: #fff">Xác nhận
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
        <?php if (!$isMobile) { ?>
            <div class="col-lg-4">
                <div id="keepposition">
                    <div class="preview-reg-fiber pad-20">
                        <div class="title-preview-reg-fiber">
                            Thông tin đơn hàng
                        </div>
                        <div class="row" style="font-weight: bold; font-size: 12px; margin-bottom: 10px">
                            <div class="col-lg-6">Tên gói :</div>
                            <div class="col-lg-6">
                                <div id="packagepreview"><?php echo $modelPackage->name ?></div>
                            </div>
                        </div>
                        <div class="row" style="font-weight: bold; font-size: 12px; margin-bottom: 10px">
                            <div class="col-lg-6">Chu kỳ :</div>
                            <div class="col-lg-6">
                                <div><?php echo WPackage::model()->getPackagePeriodLabel($modelPackage->period) ?></div>
                            </div>
                        </div>
                        <div class="row" style="font-weight: bold; font-size: 12px; margin-bottom: 10px">
                            <div class="col-lg-6">Họ và tên :</div>
                            <div class="col-lg-6">
                                <div id="fullnamepreview"></div>
                            </div>
                        </div>
                        <div class="row" style="font-weight: bold; font-size: 12px; margin-bottom: 10px">
                            <div class="col-lg-6">Số điện thoại :</div>
                            <div class="col-lg-6">
                                <div id="phonepreview"></div>
                            </div>
                        </div>
                        <div class="row" style="font-weight: bold; font-size: 12px; margin-bottom: 10px">
                            <div class="col-lg-6">Giá gói :</div>
                            <div class="col-lg-6">
                                <div id="packagepreview"><?php echo number_format($modelPackage->price) . ' VNĐ' ?></div>
                            </div>
                        </div>
                        <div class="row" style="font-weight: bold; font-size: 12px; margin-bottom: 10px">
                            <div class="col-lg-6">Phương thức thanh toán :</div>
                            <div class="col-lg-6">
                                <div>COD</div>
                            </div>
                        </div>
                        <div class="row"
                             style="font-weight: bold; font-size: 12px; margin-bottom: 10px ;<?php if ($modelPackage->commercial && $modelPackage->commercial !== '') {
                                 echo 'color:red';
                             } ?>">
                            <div class="col-lg-6">Khuyến mại :</div>
                            <div class="col-lg-6">
                                <div>
                                    <?php if (!$modelPackage->commercial || $modelPackage->commercial == '') { ?>
                                        <?php echo 'Không áp dụng'; ?>
                                    <?php } else { ?>
                                        <?php echo $modelPackage->commercial ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<style>
    .bootstrap-select {
        border: #ccc 1px solid !important;
        border-radius: 5px !important;
        width: 100% !important;
    }

    .form-reg-fiber {
        width: 100%;
        background: #fff;
        border-radius: 10px;
    }

    .preview-reg-fiber {
        width: 100%;
        background: #fff;
        border-radius: 10px;
    }

    .mar-top-bottom-20 {
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .row {
        margin-bottom: 10px;
    }

    .title-preview-reg-fiber {
        color: #ed0677;
        font-size: 22px;
        padding: 0 10px;
        text-align: center;
        margin: 10px 0;
        text-transform: uppercase;
        border-bottom: #ccc 1px solid;
    }

    .img-reg-fiber {
        width: 100%;
    }

    .select2-container {
        width: 100% !important;
    }

    #date_set_personal_id {
        width: 100% !important;
        border-bottom: none !important;
    }

    .content-order-fiber-mobile {
        text-align: left;
    }

    .w100 {
        width: 100%;
        float: left;
    }

    .w50 {
        width: 50%;
        float: left;
        padding: 5px;
    }

    .btn-default {
        background: #7CCEF0 !important;
        color: #fff;
    }

    .modal-footer-mobile {
        text-align: center !important;
        border: none !important;
    }

    #keeprelative {
        position: relative;
        width: 375px;
    }

    .checkbox {
        width: 100%;
        float: left;
    }

    .checkbox input {

    }

    .custom-row {
        margin: 15px -15px;
    }

    .containerss {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 14px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default checkbox */
    .containerss input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #eee;
    }

    /* On mouse-over, add a grey background color */
    .containerss:hover input ~ .checkmark {
        background-color: #ccc;
    }

    /* When the checkbox is checked, add a blue background */
    .containerss input:checked ~ .checkmark {
        background-color: #2196F3;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .containerss input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .containerss .checkmark:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }

    .wrap-radio {
        width: 100%;
        float: left;
    }

    .wrap-radio label {
        float: left;
        width: 20%;
    }

    .row-pho {
        display: none;
    }
</style>

<script>

    function set_value_fullname_preview() {
        var f_name = document.getElementById("WRegFiber_ten_kh").value;
        document.getElementById("fullnamepreview").innerHTML = f_name;
    }

    function set_value_phone_preview() {
        var phone = document.getElementById("WRegFiber_so_dt").value;
        document.getElementById("phonepreview").innerHTML = phone;
    }

    function set_value_personal_id_preview() {
        var pers_id = document.getElementById("WRegFiber_so_gt").value;
        document.getElementById("persionalidpreview").innerHTML = pers_id;
    }

    function set_value_date_set_personal_id_preview() {
        var date_set = document.getElementById("").value;
        document.getElementById("datesetpreview").innerHTML = date_set;
    }

    window.onload = function () {
        var f_name = document.getElementById("WRegFiber_ten_kh").value;
        document.getElementById("fullnamepreview").innerHTML = f_name;
        var phone = document.getElementById("WRegFiber_so_dt").value;
        document.getElementById("phonepreview").innerHTML = phone;
    }

    function getFiberPackageDetail() {
        $.ajax({

            url: '<?=Yii::app()->controller->createUrl("package/getdetailfiber");?>',
            method: 'POST',
            data: {
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                id: id
            },
            dataType: 'json',

            beforeSend: function () {
                $('#info-fiber-package').html("<img src='https://merchant.vban.vn/freedoo/Resources/images/preload.svg' />");
            },
            success: function (result) {
                $('#info-fiber-package').html(result.content);
            }
        });
    }

    jQuery(function ($) {
        function fixDiv() {
            var $cache = $('#keepposition');
            if ($(window).scrollTop() > 100)
                $cache.css({
                    'position': 'fixed',
                    'top': '10px',
                    'width': '375px'
                });
            else
                $cache.css({
                    'position': 'relative',
                    'top': 'auto'
                });
        }

        $(window).scroll(fixDiv);
        fixDiv();
    });

    function getPho() {
        var checked = $('#WRegFiber_chose_street')[0].checked
        if (checked == true) {
            document.getElementById("pho").style.display = "block";
            var phuong_id = document.getElementById("WRegFiber_phuong_id").value;
            var chose = document.getElementById("WRegFiber_chose_street").value;
            $.ajax({

                url: '<?=Yii::app()->controller->createUrl("package/getStreetFiber");?>',
                method: 'POST',
                data: {
                    'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                    phuong_id: phuong_id,
                    chose: chose
                },
                dataType: 'json',

                success: function (data) {
                    $('#WRegFiber_pho_id').html(data.html_street);

                }
            });
        } else {
            document.getElementById("pho").style.display = "none";
        }
    }

    function getAp() {
        var checked = $('#WRegFiber_chose_street1')[0].checked
        if (checked == true) {
            document.getElementById("ap").style.display = "block";
            var phuong_id = document.getElementById("WRegFiber_phuong_id").value;
            var chose = document.getElementById("WRegFiber_chose_street1").value;
            $.ajax({

                url: '<?=Yii::app()->controller->createUrl("package/getStreetFiber");?>',
                method: 'POST',
                data: {
                    'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                    phuong_id: phuong_id,
                    chose: chose
                },
                dataType: 'json',

                success: function (data) {
                    $('#WRegFiber_ap_id').html(data.html_street);
                }
            });
        } else {
            document.getElementById("ap").style.display = "none";
        }
    }

    function getKhu() {
        var checked = $('#WRegFiber_chose_street2')[0].checked
        if (checked == true) {
            document.getElementById("khu").style.display = "block";
            var phuong_id = document.getElementById("WRegFiber_phuong_id").value;
            var chose = document.getElementById("WRegFiber_chose_street2").value;
            $.ajax({

                url: '<?=Yii::app()->controller->createUrl("package/getStreetFiber");?>',
                method: 'POST',
                data: {
                    'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                    phuong_id: phuong_id,
                    chose: chose
                },
                dataType: 'json',

                success: function (data) {
                    $('#WRegFiber_khu_id').html(data.html_street);
                }
            });
        } else {
            document.getElementById("khu").style.display = "none";
        }
    }
</script>