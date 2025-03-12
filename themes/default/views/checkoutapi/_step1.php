<?php
    /* @var $this CheckoutController */
    /* @var $modelOrder WOrders */
    /* @var $modelSim WSim */
    /* @var $form CActiveForm */
    /* @var $modelPackage WPackage */
    /* @var $package WPackage */
    /* @var $province WProvince */
    /* @var $district WDistrict */
    /* @var $ward WWard */
    /* @var $brand_offices WBrandOffices */
    /* @var $change_sim_type */

    if(Yii::app()->session['delivery_type']){
        $delivery_type = Yii::app()->session['delivery_type'];
    }else{
        $delivery_type = false;
    }
?>
<div class="form sim_checkout">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'form_step1',
//        'action'               => Yii::app()->controller->createUrl('checkoutapi/checkout'),
        'enableAjaxValidation' => TRUE,
    )); ?>
    <!--    --><?php //echo $form->hiddenField($modelSim, 'msisdn'); ?>
    <!--    --><?php //echo $form->hiddenField($modelSim, 'price'); ?>
    <!--    --><?php //echo $form->hiddenField($modelSim, 'store_id'); ?>
    <div class="msg help-block error">
    </div>
    <div class="space_10"></div>
    <div class="title text-center">Thông tin chủ sở hữu</div>
    <div class="form-group">
        <?php echo $form->labelEx($modelSim, 'full_name', array('class' => 'label_text')); ?>
        <?php echo $form->textField($modelSim, 'full_name', array('class' => 'textbox', 'maxlength' => 255)); ?>
        <?php echo $form->error($modelSim, 'full_name'); ?>
    </div>
    <!--<div class="form-group">
        <?php //echo $form->labelEx($modelSim, 'birthday', array('class' => 'label_text')); ?>
        <?php
        //        $this->widget(
        //            'booster.widgets.TbDatePicker',
        //            array(
        //                'model'       => $modelSim,
        //                'attribute'   => 'birthday',
        //                'options'     => array(
        //                    'language' => 'vi',
        //                ),
        //                'htmlOptions' => array('placeholder' => '', 'class' => 'textbox'),
        //            )
        //        );
    ?>
        <?php //echo $form->error($modelSim, 'birthday'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($modelSim, 'personal_id', array('class' => 'label_text')); ?>
        <?php echo $form->textField($modelSim, 'personal_id', array('class' => 'textbox', 'maxlength' => 255)); ?>
        <div class="space_10"></div>
        <div class="note">
            Thông tin của Quý khách dùng để để đăng ký và kích hoạt SIM. Đề nghị Quý khách nhập thông tin chính xác và
            đảm bảo giấy tờ còn thời hạn
        </div>
        <div class="space_10"></div>
        <?php echo $form->error($modelSim, 'personal_id'); ?>
    </div>-->
    <div class="form-group">
        <?php echo $form->labelEx($modelOrder, 'phone_contact', array('class' => 'label_text')); ?>
        <?php echo $form->textField($modelOrder, 'phone_contact', array(
            'class' => 'textbox',
            'maxlength' => 255,
            'onchange'  => 'changeMsisdnPrefix(this, null);',
            'disabled'  => CFunction_MPS::isPhoneCouple($modelSim->msisdn) ? 'true' : ''
        )); ?>
        <?php echo $form->error($modelOrder, 'phone_contact'); ?>
        <div class="space_10"></div>
        <div class="note">Chúng tôi sẽ liên lạc theo SĐT này</div>
        <div class="space_10"></div>
    </div>
    <?php if ($modelPackage->range_age): ?>
        <div class="form-group">
            <?php echo CHtml::label(Yii::t('web/portal', 'year_birth'), '', array('class' => 'label_text')) ?>
            <?php
                $this->widget(
                    'booster.widgets.TbSelect2',
                    array(
                        'model'       => $modelSim,
                        'attribute'   => 'year_birth',
                        'data'        => WSim::getListYearBirth(),
                        'htmlOptions' => array(
                            'multiple' => FALSE,
                            'prompt'   => Yii::t('web/portal', 'select_year_birth'),
                        ),
                    )
                );
            ?>
            <?php echo $form->error($modelSim, 'year_birth'); ?>
        </div>
    <?php endif; ?>
<!--    --><?php //echo $form->radioButtonListGroup(
//        $modelOrder,
//        'sim_type',
//        array(
//            'widgetOptions' => array(
//                'data'        => array(
//                    WOrders::ESIM => Yii::t('web/portal', 'esim'),
//                    WOrders::NOTESIM => Yii::t('web/portal', 'simvatly'),
//                ),
//            )
//        )
//    ); ?>
    <?php echo $form->radioButtonListGroup(
        $modelOrder,
        'delivery_type',
        array(
            'widgetOptions' => array(
                'data'        => array(
                    WOrders::DELIVERY_TYPE_HOME => Yii::t('web/portal', 'delivery_home'),
                    WOrders::DELIVERY_TYPE_SHOP => Yii::t('web/portal', 'delivery_shop'),
                ),
                'htmlOptions' => array('onChange' => 'getInputByAjax(this.value);')
            )
        )
    ); ?>
    <div id="delivery_location" style="display: none; color: blue"></div>
    <div class="form-group">
        <?php echo CHtml::label(Yii::t('web/portal', 'delivery_address', array('{total_province}' => count($province))), '', array('class' => 'label_text')) ?>
    </div>
    <div class="form-group">
        <?php
            $this->widget(
                'booster.widgets.TbSelect2',
                array(
                    'model'       => $modelOrder,
                    'attribute'   => 'province_code',
                    'data'        => $province,
                    'htmlOptions' => array(
                        'multiple' => FALSE,
                        'prompt'   => Yii::t('web/portal', 'select_province'),
                        'ajax'     => array(
                            'type'   => 'POST',
                            'url'    => Yii::app()->controller->createUrl('checkoutapi/getDistrictByProvince'), //or $this->createUrl('loadcities') if '$this' extends CController
                            'update' => '#WOrders_district_code', //or 'success' => 'function(data){...handle the data in the way you want...}',
                            'data'   => array('province_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                        ),
                        'onchange' => ' $("#WOrders_district_code").select2("val", "");
                                        $("#WOrders_ward_code").select2("val", "");
                                        $("#WOrders_brand_offices").select2("val", "");
                                        $("#brand_offices_info").html("");
                                    '//reset value selected
//                        'style'=>'width:100%'
                    ),
                )
            );
        ?>
        <?php echo $form->error($modelOrder, 'province_code'); ?>
    </div>

    <div class="form-group">
        <?php
            $this->widget(
                'booster.widgets.TbSelect2',
                array(
                    'model'       => $modelOrder,
                    'attribute'   => 'district_code',
                    'data'        => $district,
                    'htmlOptions' => array(
                        'multiple' => FALSE,
                        'prompt'   => Yii::t('web/portal', 'select_district'),
                        'ajax'     => array(
                            'type'     => 'POST',
                            'dataType' => 'json',
                            'url'      => Yii::app()->controller->createUrl('checkoutapi/getWardBrandOfficesByDistrict'), //or $this->createUrl('loadcities') if '$this' extends CController
//                            'update' => '#WOrders_ward_code', //or 'success' => 'function(data){...handle the data in the way you want...}',
                            'success'  => 'function(data){
                                                $("#WOrders_ward_code").html(data.html_ward);
                                                $("#WOrders_brand_offices").html(data.html_brand_offices);
                                            }',
                            'data'     => array('district_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                        ),
                        'onchange' => '$("#WOrders_ward_code").select2("val", "");
                                            $("#WOrders_brand_offices").select2("val", "");
                                            $("#brand_offices_info").html("");
                                        '//reset value selected
                    ),
                )
            );
        ?>
        <?php echo $form->error($modelOrder, 'district_code'); ?>
    </div>
    <?php
        $address = '';
        $brand   = 'display:none;';
        if ($modelOrder->delivery_type == WOrders::DELIVERY_TYPE_SHOP) {
            $address = 'display:none;';
            $brand   = '';
        }
    ?>
    <div id="box_address_home" class="form-group" style="<?= $address; ?>">
        <div class="form-group">
            <?php
                $this->widget(
                    'booster.widgets.TbSelect2',
                    array(
                        'model'       => $modelOrder,
                        'attribute'   => 'ward_code',
                        'data'        => $ward,
                        'htmlOptions' => array(
                            'multiple' => FALSE,
                            'prompt'   => Yii::t('web/portal', 'select_ward'),
//                            'ajax'     => array(
//                                'type'   => 'POST',
//                                'url'    => Yii::app()->controller->createUrl('checkoutapi/getListBrandOffices'), //or $this->createUrl('loadcities') if '$this' extends CController
//                                'update' => '#WOrders_brand_offices', //or 'success' => 'function(data){...handle the data in the way you want...}',
//                                'data'   => array('ward_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
//                            ),
                        ),
                    )
                );
            ?>
            <?php echo $form->error($modelOrder, 'ward_code'); ?>
        </div>
        <?php echo $form->textField($modelOrder, 'address_detail', array('class' => 'textbox', 'maxlength' => 255, 'placeholder' => Yii::t('web/portal', 'address_detail'))); ?>
        <?php echo $form->error($modelOrder, 'address_detail'); ?>
    </div>

    <div id="box_address_shop" class="form-group" style="<?= $brand; ?>">
        <?php
            $this->widget(
                'booster.widgets.TbSelect2',
                array(
                    'model'       => $modelOrder,
                    'attribute'   => 'brand_offices',
                    'data'        => $brand_offices,
                    'htmlOptions' => array(
                        'multiple' => FALSE,
                        'prompt'   => Yii::t('web/portal', 'brand_offices'),
                        'ajax'     => array(
                            'type'   => 'POST',
                            'url'    => Yii::app()->controller->createUrl('checkoutapi/getBrandOfficesInfo'),
                            'update' => '#brand_offices_info',
                            'data'   => array('brand_offices' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                        ),
                    ),
                )
            );
        ?>
        <?php echo $form->error($modelOrder, 'brand_offices'); ?>
        <div class="space_10"></div>
        <div id="brand_offices_info"></div>
    </div>
    <div class="form-group">
        <?php echo CHtml::label('THỜI GIAN GIAO HÀNG', '', array('class' => 'label_text')) ?>
        <div class="space_10"></div>
        <div class="note">Từ 8h00 - 18h00 tất cả các ngày trong tuần trừ ngày Lễ Tết</div>
        <div class="space_10"></div>
    </div>
    <?php
        /*if ($change_sim_type) {//term=0 && price_term=0
            echo $form->radioButtonListGroup(
                $modelSim,
                'type',
                array(
                    'widgetOptions' => array(
                        'data'        => array(
                            WSim::TYPE_PREPAID  => Yii::t('web/portal', 'prepaid'),
                            WSim::TYPE_POSTPAID => Yii::t('web/portal', 'postpaid'),
                        ),
                        'htmlOptions' => array(
                            'onClick' => 'getPackageByType(this.value);',
                        )
                    )
                )
            );
        } else {*/
            echo $form->hiddenField($modelSim, 'type');
        //} ?>
    <div class="form-group">
        <?php echo $form->labelEx($modelOrder, 'customer_note', array('class' => 'label_text')); ?>
        <?php echo $form->textField($modelOrder, 'customer_note', array('class' => 'textbox', 'maxlength' => 255)); ?>
        <?php echo $form->error($modelOrder, 'customer_note'); ?>
        <div class="space_10"></div>
        <div class="note">Ví dụ: Giao hàng trong giờ hành chính</div>
        <div class="space_10"></div>
    </div>
    <div class="line"></div>
<!--    <div class="font_16">-->
<!--        --><?php //echo $form->error($modelOrder, 'package'); ?>
<!--    </div>-->
<!--    <div class="title text-center" data-toggle="collapse" data-target="#pack_collapse" style="cursor: pointer">-->
<!--        <div class="space_10"></div>-->
<!--        <i class="fa fa-minus" aria-hidden="true"></i> Chọn gói cước-->
<!---->
<!--        <div class="space_10"></div>-->
<!--    </div>-->
<!--    <div id="pack_collapse" class="collapse in">-->
<!--        --><?php
//            $this->renderPartial('_list_package', array(
//                'package'      => $package,
//                'modelPackage' => $modelPackage
//            ));
//        ?>
<!--    </div>-->

    <div class="space_10"></div>
    <!--<div class="line"></div>
    <div class="title text-center">Nạp tiền cho Sim</div>
    <div class="space_10"></div>

    <div class="form-group">
        <?php /*echo $form->labelEx($modelOrder, 'card', array('class' => 'label_text')); */ ?>
        <?php
        /*$card = Yii::app()->params['card_value'];
        $this->widget(
            'booster.widgets.TbSelect2',
            array(
                'model'       => $modelOrder,
                'attribute'   => 'card',
                'data'        => $card,
                'htmlOptions' => array(
                    'multiple' => FALSE,
                    'prompt'   => Yii::t('web/portal', 'choose_card_value'),
                    'onchange' => 'autoFillToOrderSim();',
                ),
            )
        );*/ ?>
    </div>-->
    <?php if (empty($modelOrder->invitation)): ?>
        <div class="line"></div>
        <div class="title text-center">Mã khuyến mại/Mã CTV giới thiệu (nếu có)</div>
        <div class="space_10"></div>
        <div class="form-group">
            <?php echo $form->labelEx($modelOrder, 'promo_code', array('class' => 'label_text')); ?>
            <?php echo $form->textField($modelOrder, 'promo_code', array('class' => 'textbox', 'maxlength' => 255)); ?>
            <?php echo $form->error($modelOrder, 'promo_code'); ?>

            <div class="space_10"></div>
            <!--<div class="note">Nhập mã giới thiệu để được hưởng thêm ưu đãi 10.000 đồng vào TKKM2</div>-->
            <div class="space_10"></div>
        </div>
    <?php endif; ?>
    <div class="space_10"></div>
    <div class="text-center">
        <a href="javascript:void(0);" onclick="displayWarning();" class="btn btn_return">
            Quay lại
        </a>
        <?php echo CHtml::submitButton(Yii::t('web/portal', 'continue'), array('class' => 'btn btn_continue')); ?>
    </div>
    <?php $this->endWidget(); ?>
    <div class="space_10"></div>
</div>

<script>
    function getInputByAjax(delivery_type) {
        getOrderPrice();
        if (delivery_type == <?=WOrders::DELIVERY_TYPE_SHOP?>) {
            $('#box_address_shop').css('display', 'block');
            $('#box_address_home').css('display', 'none');
        } else {
            $('#box_address_home').css('display', 'block');
            $('#box_address_shop').css('display', 'none');
            $('#WOrders_address_detail').val('');
        }
    }

    //radio button sim_type onclick
    function getPackageByType(sim_type) {
        getOrderPrice();
        $("#pack_collapse").collapse('hide');
        //warning required select package
        var tag_error_package = $("#WOrders_package_em_");
        if (sim_type == <?=WSim::TYPE_PREPAID?>) {
            tag_error_package.html("<?=Yii::t('web/portal', 'warning_required_package')?>");
            tag_error_package.css("display", "block");
        } else {
            tag_error_package.html("");
        }
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('checkoutapi/getPackageByType');?>",
            crossDomain: true,
            dataType: 'json',
            data: {sim_type: sim_type, YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"},
            success: function (result) {
                $('#pack_collapse').html(result.html_package);
                $('#WOrders_province_code').html(result.html_province);
                $("#WOrders_district_code").select2("val", "");
                $("#WOrders_ward_code").select2("val", "");
                $("#WOrders_brand_offices").select2("val", "");
                $("#WOrders_address_detail").val("");
                $("#brand_offices_info").html("");
            }
        });
    }

    //pack_collapse on show
    $(window).on('load', function () {
        //window on load->slider package
        showListPackage();
        $('#pack_collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".fa-plus").removeClass("fa-plus").addClass("fa-minus");
            //collapse->slider package
            showListPackage();
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".fa-minus").removeClass("fa-minus").addClass("fa-plus");
        });
    });

    /**
     * Slider list package
     * onclick select package -> get order price
     */
    function showListPackage() {
        $('#checkout_slider').owlCarousel({
            autoplay: true,
            autoplayTimeout: 5000,
            loop: false,//set false->checkbox checked(important)
            nav: true,
            navText: ['<i class="fa fa-chevron-circle-left"></i>', '<i class="fa fa-chevron-circle-right"></i>'],
            pagination: false,
            stopOnHover: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 1
                },
                1000: {
                    items: 2
                }
            }
        });

        //select only one checkbox in group
        //onclick label checkbox
        $('#checkout_slider label').on('click', function (e) {
            var element = $(this).prev('input.chk_package').attr('id');
            if (document.getElementById(element).checked) {
                element.checked = false;
                //reset auto slider unchecked
                $('#checkout_slider').trigger('play.owl.autoplay', [5000]);
            } else {
                element.checked = true;
                $(this).prev('input.chk_package').not(this).prop('checked', false);
                //stop auto slider checked
                $('#checkout_slider').trigger('stop.owl.autoplay');
            }
        });

        //select only one checkbox in group
        //onclick checkbox
        $('input.chk_package').on('change', function () {
            $('input.chk_package').not(this).prop('checked', false);
            getOrderPrice();
        });
    }

    /**
     * Tinh gia cho don hang
     */
    function getOrderPrice() {
        var form_data = new FormData(document.getElementById("form_step1"));//formID
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('checkoutapi/getOrderPrice');?>",
            crossDomain: true,
            dataType: 'json',
            data: form_data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            success: function (result) {
                $('#order_price_temp').html(result.content);
            }
        });
    }
    var delivery_type = '<?= $delivery_type ?>';
    console.log(delivery_type);
    if(delivery_type == 2){ // điểm giao dịch
        $('#WOrders_delivery_type_0').prop('disabled', true);
        $('#box_address_shop').css('display', 'block');
        $('#box_address_home').css('display', 'none');
        $('#delivery_location').text('Đơn hàng chỉ nhận giao tại ĐGD').css('display', 'block');
    }else if(delivery_type == 1){ // tại nhà
        $('#WOrders_delivery_type_1').prop('disabled', true);
        $('#box_address_shop').css('display', 'none');
        $('#box_address_home').css('display', 'block');
        $('#delivery_location').text('Đơn hàng chỉ nhận giao tại nhà').css('display', 'block');
    }
</script>