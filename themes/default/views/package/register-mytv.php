<?php
$detect = new MyMobileDetect();
$isMobile = $detect->isMobile();
?>
<div class="container">
    <div class="content-finish-register">
        <div class="col-md-8">
            <div class="wrap">
                <div class="title-finish-register">
                    THÔNG TIN ĐẶT HÀNG
                </div>
                <div class="form-tt">
                    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id' => 'form_register_mytv',
                        'action' => Yii::app()->controller->createUrl('package/registermytv', array('package' => $modelPackage->id)),
                        'enableAjaxValidation' => FALSE,
                        'enableClientValidation' => FALSE,

                    )); ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <?php if (isset($mes)) { ?>
                                <div class="mes-api" style="color: red">
                                    <?php echo $mes; ?>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 custom-label"><label>Tên khách hàng <span
                                        style="color: red">(*)</span></label>
                        </div>
                        <div class="col-lg-7">
                            <?php echo $form->textField($modelRegFiber, 'ten_kh', array('class' => 'form-control', 'maxlength' => 255, 'onchange' => 'set_value_fullname_preview()')); ?>
                            <?php echo $form->error($modelRegFiber, 'ten_kh'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 custom-label"><label>Điện thoại liên hệ lắp đặt <span
                                        style="color: red">(*)</span></label></div>
                        <div class="col-lg-7">
                            <?php echo $form->textField($modelRegFiber, 'so_dt', array('class' => 'form-control', 'maxlength' => 255, 'onchange' => 'set_value_phone_preview()')); ?>
                            <?php echo $form->error($modelRegFiber, 'so_dt'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 custom-label"><label>Tỉnh/TP <span style="color: red">(*)</span></label>
                        </div>
                        <div class="col-lg-7">
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
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 custom-label"><label>Quận/huyện <span style="color: red">(*)</span></label>
                        </div>
                        <div class="col-lg-7">
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
//                                    'ajax' => array(
//                                        'type' => 'POST',
//                                        'dataType' => 'json',
//                                        'url' => Yii::app()->controller->createUrl('package/getWardBrandOfficesByDistrict_mytv'),
//                                        'success' => 'function(data){
//                                                            $("#WRegFiber_phuong_id").html(data.html_ward);
//                                                        }',
//                                        'data' => array('tinh_id'=>'$("#WRegFiber_tinh_id").select2("val", "")','quan_id' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
//                                    ),
                                        'onchange' => 'get_phuong()'
                                    ),
                                )
                            );
                            ?>
                            <?php echo $form->error($modelRegFiber, 'quan_id'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 custom-label"><label>Phường/Xã<span style="color: red">(*)</span></label>
                        </div>
                        <div class="col-lg-7">
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
                                        'onchange' => 'showChoses()'
                                    ),
                                )
                            );
                            ?>
                            <?php echo $form->error($modelRegFiber, 'phuong_id'); ?>
                        </div>
                    </div>
                    <div class="row" id="Choses">
                        <div class="col-lg-5"></div>
                        <div class="col-lg-7">
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
                        <?php echo $form->error($modelRegFiber, 'chose_street'); ?>
                    </div>
                    <div class="row pho_custom" id="Pho">
                        <div class="col-lg-5 custom-label"><label>Phố<span style="color: red">(*)</span></label></div>
                        <div class="col-lg-7">
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
                    <div class="row pho_custom" id="Ap">
                        <div class="col-lg-5 custom-label"><label>Ấp<span style="color: red">(*)</span></label></div>
                        <div class="col-lg-7">
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
                    <div class="row pho_custom" id="Khu">
                        <div class="col-lg-5 custom-label"><label>Khu<span style="color: red">(*)</span></label></div>
                        <div class="col-lg-7">
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
                    <div class="row">
                        <div class="col-lg-5 custom-label"><label>Số nhà<span style="color: red">(*)</span></label>
                        </div>
                        <div class="col-lg-7">
                            <?php echo $form->textField($modelRegFiber, 'so_nha', array('class' => 'form-control', 'maxlength' => 255, 'placeholder' => 'Địa chỉ số nhà', 'onclick' => 'addressdetail();')); ?>
                            <?php echo $form->error($modelRegFiber, 'so_nha'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 custom-label"><label>Có sử dụng bộ giải mã STB hay không<span
                                        style="color: red">(*)</span></label>
                        </div>
                        <div class="col-lg-7">
                            <div class="radio-toolbar">
                                <input type="radio" id="radio1" name="WRegFiber[stb_use]" value="1"
                                       onclick="check_stb('yes',<?php echo $modelPackage->period ?>,<?php echo $modelPackage->price_discount ?>,<?php echo $modelPackage->price_stb ?>)">
                                <label for="radio1">Có</label>

                                <input type="radio" id="radio2" name="WRegFiber[stb_use]" value="0" checked
                                       onclick="check_stb('no',<?php echo $modelPackage->period ?>,<?php echo $modelPackage->price_discount ?>,<?php echo $modelPackage->price_no_stb ?>)">
                                <label for="radio2">Không</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 custom-label" style="text-align: left; margin-left: 40px">
                            (Bộ giải mã STB sử dụng cho TV thông thường không có kết nối Internet)
                        </div>
                    </div>
                   <!-- <div class="row">
                        <div class="col-lg-5 custom-label"><label>Mã khuyến mại / Mã CTV</label></div>
                        <div class="col-lg-7">
                            <?php /*echo $form->textField($modelRegFiber, 'promo_code', array('class' => 'form-control', 'maxlength' => 255, 'placeholder' => 'Mã CTV / Mã khuyến mại (Nếu có)')); */?>
                            <?php /*echo $form->error($modelRegFiber, 'promo_code'); */?>
                        </div>
                    </div>-->
                    <div class="row">
                        <div class="col-lg-5"></div>
                        <div class="col-lg-7">
                            <?php if (!$isMobile) { ?>
                                <button class="btn btn-register"
                                        style="background: #ed0677 !important; color: #fff; margin-top: 20px">Đăng ký
                                </button>
                            <?php } else { ?>
                                <a class="btn btn-register"
                                   style="background: #ed0677 !important; color: #fff; margin-top: 20px"
                                   data-toggle="modal"
                                   data-target="#previeworder">Đăng ký
                                </a>
                                <!-- Modal -->
                                <div id="previeworder" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
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
                                                    <div class="w50">
                                                        <div id="price_pre">
                                                            <?php if ($modelPackage->price_no_stb) { ?>
                                                                <?php echo number_format($modelPackage->price_no_stb) . ' VNĐ' ?>
                                                            <?php } ?>
                                                        </div>
                                                        <div id="priceSale"></div>
                                                    </div>
                                                </div>
                                                <div class="w100">
                                                    <div class="w50">
                                                        STB :
                                                    </div>
                                                    <div class="w50">
                                                        <div class="radio-toolbar">
                                                            <span id="stbCustom"
                                                                  style="width100%;color: #ed0677 !important; border-radius: 0px; text-align: left">Có</span>
                                                        </div>
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
                                                            <?php if (!$modelPackage->commercial || $modelPackage->commercial == '') { ?>
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
        </div>
        <?php if (!$isMobile) { ?>
            <div class="col-md-4">
                <div class="info-order">
                    <div class="title-info-order">
                        Thông tin đơn hàng
                    </div>
                    <div class="content-info-order">
                        <div class="row">
                            <div class="col-md-6">Tên gói :</div>
                            <div class="col-md-6"><?php echo $modelPackage->name ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">Chu kỳ :</div>
                            <div class="col-md-6"><?php echo WPackage::model()->getPackagePeriodLabel($modelPackage->period) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">STB :</div>
                            <div class="col-md-6"><span id="stbCustom"
                                                        style="width100%;color: #ed0677 !important; border-radius: 0px; text-align: left">Có</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">Họ và tên :</div>
                            <div class="col-md-6">
                                <div id="fullnamepreview"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">Số điện thoại :</div>
                            <div class="col-md-6">
                                <div id="phonepreview"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">Giá gói :</div>
                            <div class="col-md-6">
                                <div id="price_pre">
                                    <?php if ($modelPackage->price_no_stb) { ?>
                                        <?php echo number_format($modelPackage->price_no_stb) . ' VNĐ' ?>
                                    <?php } ?>
                                </div>
                                <div id="priceSale"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">Hình thức thanh toán :</div>
                            <div class="col-md-6">COD</div>
                        </div>
                        <div class="row"
                             style="<?php if ($modelPackage->commercial && $modelPackage->commercial !== '') {
                                 echo 'color:red';
                             } ?>">
                            <div class="col-md-6">Khuyến mại :</div>
                            <div class="col-md-6">
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
        <?php } ?>
    </div>
</div>
<style>
    .content-info-order {
        width: 100%;
        float: left;
        padding: 10px;
    }

    .content-info-order .row {
        margin-bottom: 10px;
    }

    .title-info-order {
        width: 100%;
        float: left;
        text-transform: uppercase;
        font-size: 25px;
        text-align: center;
        border-bottom: 1px #ccc solid;
    }

    .info-order {
        width: 100%;
        float: left;
        padding: 20px 0px;
        box-shadow: 0 0 10px #ccc;
        height: 300px;
    }

    .title-finish-register {
        width: 100%;
        float: left;
        text-align: center;
        text-transform: uppercase;
        font-size: 25px;
        padding: 20px 0px;
    }

    .content-finish-register {
        width: 100%;
        float: left;
        margin-top: 20px;
    }

    .wrap {
        width: 100%;
        float: left;
        background: #fff;
        border: #ccc 1px dotted;
        margin-bottom: 20px;
        border-radius: 2px;
    }

    .form-tt {
        width: 100%;
        float: left;
        padding: 0px 30px;
    }

    .custom-label {
        float: left;
        text-align: right;
        margin-top: 9px;
    }

    .form-tt .row {
        margin-bottom: 15px;
    }

    #s2id_WRegFiber_tinh_id {
        width:: 100% !important;
    }

    .select2-container {
        width: 100% !important;
    }

    #Pho {
        display: none;
    }

    #Ap {
        display: none;
    }

    #Khu {
        display: none;
    }

    #Choses {
        display: none;
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

    .radio-toolbar input[type="radio"] {
        display: none;
    }

    .radio-toolbar label {
        display: inline-block;
        background-color: #ddd;
        padding: 4px 11px;
        font-family: Arial;
        font-size: 16px;
        cursor: pointer;
    }

    .radio-toolbar input[type="radio"]:checked + label {
        background-color: #ed0677 !important;
        color: #fff;
    }
    #stbCustom{
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
        document.getElementById("stbCustom").style.display = "none";
    }

    function get_phuong() {
        var quan_id = document.getElementById("WRegFiber_quan_id").value;
        var tinh_id = document.getElementById("WRegFiber_tinh_id").value;
        $.ajax({

            url: '<?=Yii::app()->controller->createUrl("package/getWardBrandOfficesByDistrict_mytv");?>',
            method: 'POST',
            data: {
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                quan_id: quan_id,
                tinh_id: tinh_id
            },
            dataType: 'json',

            success: function (data) {
                $('#WRegFiber_phuong_id').html(data.html_ward);
            }
        });
    }

    function showChoses() {
        document.getElementById("Choses").style.display = "block";
    }

    function getPho() {
        var checked = $('#WRegFiber_chose_street')[0].checked
        if (checked == true) {
            document.getElementById("Pho").style.display = "block";
            var tinh_id = document.getElementById("WRegFiber_tinh_id").value;
            var phuong_id = document.getElementById("WRegFiber_phuong_id").value;
            var chose = document.getElementById("WRegFiber_chose_street").value;
            $.ajax({

                url: '<?=Yii::app()->controller->createUrl("package/getStreetMyTV");?>',
                method: 'POST',
                data: {
                    'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                    phuong_id: phuong_id,
                    chose: chose,
                    tinh_id: tinh_id
                },
                dataType: 'json',

                success: function (data) {
                    $('#WRegFiber_pho_id').html(data.html_street);
                }
            });
        } else {
            document.getElementById("Pho").style.display = "none";
        }

    }

    function getAp() {
        var checked = $('#WRegFiber_chose_street1')[0].checked
        if (checked == true) {
            document.getElementById("Ap").style.display = "block";
            var tinh_id = document.getElementById("WRegFiber_tinh_id").value;
            var phuong_id = document.getElementById("WRegFiber_phuong_id").value;
            var chose = document.getElementById("WRegFiber_chose_street1").value;
            $.ajax({

                url: '<?=Yii::app()->controller->createUrl("package/getStreetMyTV");?>',
                method: 'POST',
                data: {
                    'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                    phuong_id: phuong_id,
                    chose: chose,
                    tinh_id: tinh_id
                },
                dataType: 'json',

                success: function (data) {
                    $('#WRegFiber_ap_id').html(data.html_street);
                }
            });
        } else {
            document.getElementById("Ap").style.display = "none";
        }

    }

    function getKhu() {
        var checked = $('#WRegFiber_chose_street2')[0].checked
        if (checked == true) {
            document.getElementById("Khu").style.display = "block";
            var tinh_id = document.getElementById("WRegFiber_tinh_id").value;
            var phuong_id = document.getElementById("WRegFiber_phuong_id").value;
            var chose = document.getElementById("WRegFiber_chose_street2").value;
            $.ajax({

                url: '<?=Yii::app()->controller->createUrl("package/getStreetMyTV");?>',
                method: 'POST',
                data: {
                    'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                    phuong_id: phuong_id,
                    chose: chose,
                    tinh_id: tinh_id
                },
                dataType: 'json',

                success: function (data) {
                    $('#WRegFiber_khu_id').html(data.html_street);
                }
            });
        } else {
            document.getElementById("Khu").style.display = "none";
        }

    }

    function check_stb(input_check, period, price_discount, price) {
        if (input_check === 'yes') {
            document.getElementById("stbCustom").style.display = "block";
            document.getElementById("price_pre").style.display = "none";
            document.getElementById("priceSale").style.display = "block";
            document.getElementById("priceSale").innerHTML = formatNumberValue(price) + ' VNĐ';
        } else if (input_check === 'no') {
            document.getElementById("stbCustom").style.display = "none";
            document.getElementById("priceSale").style.display = "none";
            document.getElementById("price_pre").style.display = "block";
            document.getElementById("stbCustom").style.display = "none";

        }
    }

    // window.onload = function (e) {
    //     $('input:radio[name="WRegFiber[stb_use]"]')[1].click(function () {
    //         $('input:radio[name="WRegFiber[stb_use]"]')[1].checked = true;
    //     });
    //
    // }
</script>