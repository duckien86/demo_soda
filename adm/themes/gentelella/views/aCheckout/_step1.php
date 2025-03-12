<?php
    /* @var $this CheckoutController */
    /* @var $modelOrder AOrders */
    /* @var $modelSim ASim */
    /* @var $form CActiveForm */
    /* @var $modelPackage WPackage */
    /* @var $package WPackage */
    /* @var $province WProvince */
    /* @var $district WDistrict */
    /* @var $ward WWard */
    /* @var $brand_offices WBrandOffices */
    /* @var $change_sim_type */
    //    if (Yii::app()->cache->get('createSim_' . $modelOrder->id)){
    //        echo '<script>displayWarning();</script>';
    //    }
?>
<div class="form sim_checkout">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'form_step1',
        'action'               => Yii::app()->controller->createUrl('aCheckout/checkout'),
        'enableAjaxValidation' => TRUE,
    )); ?>
    <!--    --><?php //echo $form->hiddenField($modelSim, 'msisdn'); ?>
    <!--    --><?php //echo $form->hiddenField($modelSim, 'price'); ?>
    <!--    --><?php //echo $form->hiddenField($modelSim, 'store_id'); ?>
    <div class="msg help-block error">
    </div>

    <div class="form-group">
        <?php echo $form->hiddenField($modelSim, 'full_name', array('class' => 'textbox', 'maxlength' => 255)); ?>
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
        <?php echo $form->hiddenField($modelSim, 'personal_id', array('class' => 'textbox', 'maxlength' => 255)); ?>
        <div class="space_10"></div>
        <div class="note">
            Thông tin của Quý khách dùng để để đăng ký và kích hoạt SIM. Đề nghị Quý khách nhập thông tin chính xác và
            đảm bảo giấy tờ còn thời hạn
        </div>
        <div class="space_10"></div>
        <?php echo $form->error($modelSim, 'personal_id'); ?>
    </div>-->
    <div class="form-group">
        <?php echo $form->hiddenField($modelOrder, 'phone_contact', array('class' => 'textbox', 'maxlength' => 255)); ?>
        <?php echo $form->error($modelOrder, 'phone_contact'); ?>

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
                        'data'        => ASim::getListYearBirth(),
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


    <div id="box_address_home" class="form-group" style="<?= $address; ?>">

        <?php echo $form->hiddenField($modelOrder, 'address_detail', array('class' => 'textbox', 'maxlength' => 255, 'placeholder' => Yii::t('web/portal', 'address_detail'))); ?>
        <?php echo $form->error($modelOrder, 'address_detail'); ?>
    </div>

    <?php echo $form->hiddenField($modelOrder, 'delivery_type', array('class' => 'textbox', 'maxlength' => 255)); ?>
    <?php echo $form->hiddenField($modelOrder, 'payment_method', array('class' => 'textbox', 'maxlength' => 255)); ?>
    <?php echo $form->hiddenField($modelOrder, 'province_code', array('class' => 'textbox', 'maxlength' => 255)); ?>
    <?php echo $form->hiddenField($modelOrder, 'ward_code', array('class' => 'textbox', 'maxlength' => 255)); ?>
    <?php echo $form->hiddenField($modelOrder, 'district_code', array('class' => 'textbox', 'maxlength' => 255)); ?>

    <?php
        /*if ($change_sim_type) {//term=0 && price_term=0
            echo $form->radioButtonListGroup(
                $modelSim,
                'type',
                array(
                    'widgetOptions' => array(
                        'data'        => array(
                            ASim::TYPE_PREPAID  => Yii::t('web/portal', 'prepaid'),
                            ASim::TYPE_POSTPAID => Yii::t('web/portal', 'postpaid'),
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

    <div class="line"></div>
    <div class="font_16">
        <?php echo $form->error($modelOrder, 'package'); ?>
    </div>
    <?php
        $this->renderPartial('_list_package', array(
            'package'      => $package,
            'modelPackage' => $modelPackage
        ));
    ?>


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
        if (delivery_type == <?=AOrders::DELIVERY_TYPE_SHOP?>) {
            $('#box_address_shop').css('display', 'block');
            $('#box_address_home').css('display', 'none');
        } else {
            $('#box_address_home').css('display', 'block');
            $('#box_address_shop').css('display', 'none');
            $('#AOrders_address_detail').val('');
        }
    }

    //radio button sim_type onclick
    function getPackageByType(sim_type) {
        getOrderPrice();
        $("#pack_collapse").collapse('hide');
        //warning required select package
        var tag_error_package = $("#AOrders_package_em_");
        if (sim_type == <?=ASim::TYPE_PREPAID?>) {
            tag_error_package.html("<?=Yii::t('web/portal', 'warning_required_package')?>");
            tag_error_package.css("display", "block");
        } else {
            tag_error_package.html("");
        }
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('aCheckout/getPackageByType');?>",
            crossDomain: true,
            dataType: 'json',
            data: {sim_type: sim_type, YII_CSRF_TOKEN: "<?=Yii::app()->request->csrfToken;?>"},
            success: function (result) {
                $('#pack_collapse').html(result.html_package);
                $('#AOrders_province_code').html(result.html_province);
                $("#AOrders_district_code").select2("val", "");
                $("#AOrders_ward_code").select2("val", "");
                $("#AOrders_brand_offices").select2("val", "");
                $("#AOrders_address_detail").val("");
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
//        $('#checkout_slider').owlCarousel({
//            autoplay: true,
//            autoplayTimeout: 5000,
//            loop: false,//set false->checkbox checked(important)
//            nav: true,
//            navText: ['<i class="fa fa-chevron-circle-left"></i>', '<i class="fa fa-chevron-circle-right"></i>'],
//            pagination: false,
//            stopOnHover: true,
//            responsiveClass: true,
//            responsive: {
//                0: {
//                    items: 1
//                },
//                480: {
//                    items: 1
//                },
//                1000: {
//                    items: 2
//                }
//            }
//        });

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
            url: "<?=Yii::app()->controller->createUrl('aCheckout/getOrderPrice');?>",
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
</script>