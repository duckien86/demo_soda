<?php
    /* @var $this PackageController */
    /* @var $modelPackage WPackage */
    /* @var $modelOrder WOrders */
    /* @var $orderDetails WOrderDetails */
    /* @var $form CActiveForm */
?>
<script src='https://www.google.com/recaptcha/api.js?hl=vi'></script>
<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <section class="ss-bg">
        <div class="container">
            <div class="checkout-process">
                <div class="col-md-4 col-md-push-8 no_pad_sm no_pad_xs">
                    <div id="main_right_section">
                        <?php $this->renderPartial('_panel_order', array(
                            'modelOrder'   => $modelOrder,
                            'modelPackage' => $modelPackage,
                        )); ?>
                    </div>
                </div>
                <div class="col-md-8 col-md-pull-4 no_pad">
                    <div id="main_left_section">
                        <div class="form_title">
                            Đăng ký gói cước: <span><?= CHtml::encode($modelPackage->name); ?></span>
                        </div>
                        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                            'id'                     => 'register_package',
                            'action'                 => Yii::app()->controller->createUrl('package/register', array('package' => $modelPackage->id)),
                            'enableAjaxValidation'   => TRUE,
                            'enableClientValidation' => TRUE,
                        )); ?>
                        <?php echo $form->hiddenField($modelPackage, 'id'); ?>
                        <?php echo $form->hiddenField($modelPackage, 'code'); ?>
                        <div class="form-group">
                            <?php echo CHtml::label(Yii::t('web/portal', 'input_phone_contact'), 'WOrders_phone_contact', array('class' => 'label_text')) ?>
                            <?php
                                /* comment: SIM_FREEDOO && VipUser
                                 * if (WPackage::checkVipUser() && ($modelPackage->vip_user >= WPackage::VIP_USER)
                                    && $modelOrder->phone_contact
                                ) {
                                    echo $form->textField($modelOrder, 'phone_contact', array(
                                        'class'     => 'textbox_lg',
                                        'maxlength' => 255,
                                        'readOnly'  => TRUE,//disable
                                        'onchange'  => 'autoFillToOrderPackage();'
                                    ));
                                } else {*/
                                echo $form->textField($modelOrder, 'phone_contact', array(
                                    'class'     => 'textbox_lg',
                                    'maxlength' => 255,
                                    'onchange'  => 'autoFillToOrderPackage();changeMsisdnPrefix(this, autoFillToOrderPackage);checkKHDN(this)',

                                ));
                                /*}*/
                            ?>
                            <div id="mes" style="color: #ed0677"></div>
                            <?php echo $form->error($modelOrder, 'phone_contact'); ?>

                        </div>
                       <!-- <div class="form-group" id="promoCode">
                            <?php /*echo $form->labelEx($modelOrder, 'promo_code', array('class' => 'label_text')); */?>
                            <?php /*echo $form->textField($modelOrder, 'promo_code', array('class' => 'textbox_lg', 'maxlength' => 255)); */?>
                            <?php /*echo $form->error($modelOrder, 'promo_code'); */?>
                        </div>-->
                        <div class="form-group">
                            <div id="captcha_place_holder"
                                 class="g-recaptcha"
                                 data-sitekey="6LeeB0saAAAAAAsEYp1XIhXlVS3fwyC7qTvLaNUK"></div>								 
                            <?php echo $form->error($modelOrder, 'captcha'); ?>
                        </div>
                        <div class="space_10"></div>
                        <div class="package_info text-center">
                            <?php echo CHtml::link('Quay lại', Yii::app()->controller->createUrl('package/detail', array('slug' => $modelPackage->slug)), array('class' => 'btn btn_register uppercase')); ?>
                            <?php
                                if ($modelPackage->price_discount > 0) {
                                    echo CHtml::button('Tiếp tục', array('onclick' => 'registerPriceDiscount();', 'class' => 'btn btn_register uppercase'));
                                } else {
                                    echo CHtml::submitButton('Tiếp tục', array('class' => 'btn btn_register uppercase'));
                                }
                            ?>
                        </div>

                        <?php $this->endWidget(); ?>
                        <div class="space_10"></div>
                    </div>
                </div>
                <!-- end #main_right_section -->
                <div class="space_30"></div>
            </div>
        </div>
    </section>
    <?php $this->renderPartial('_modal_confirm_register', array('package' => $modelPackage)); ?>
</div>
<!-- Modal -->
<div id="modalKHDN" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Thông báo</h4>
            </div>
            <div class="modal-body">
                <p>Bạn không thể nhập mã CTV cho thuê bao do ĐLTC phát triển</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Thoát</button>
            </div>
        </div>

    </div>
</div>
<script>
    function checkbyapi() {
        var msisdn = document.getElementById('WOrders_phone_contact').value;
        $.ajax({
            url: '<?=Yii::app()->controller->createUrl("package/checkInfoPhone");?>',
            dataType: 'json',
            method: 'POST',
            data: {
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                msisdn: msisdn
            },
            success: function (resp) {
                $('#mes').html(resp.msg);
            },

        });
    }

    function registerPriceDiscount() {
        var form_data = new FormData(document.getElementById("register_package"));//formID
        $(':input[type="submit"]').prop('disabled', true);
        checkDiscountPrice("<?=Yii::app()->controller->createUrl('package/checkDiscountPrice');?>", form_data);
    }

    function checkDiscountPrice(url, form_data) {
        $.ajax({
            type: "POST",
            url: url,
            crossDomain: true,
            dataType: 'json',
            data: form_data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            success: function (result) {
                if (result.status == true) {
                    $('#msg_confirm_register').html(result.content);
                    $('#confirm_register').modal('show');
                } else {
                    $(':input[type="submit"]').prop('disabled', false);
                    //display error all field
                    $.each(result, function (key, val) {
                        $("#register_package #" + key + "_em_").text(val);
                        $("#register_package #" + key + "_em_").show();
                    });
                }
            }
        });
    }

    // close modal
    $('#confirm_register').on('hidden.bs.modal', function () {
        window.location.href = '<?=Yii::app()->controller->createUrl('package/index');?>';
    });
    function checkKHDN(msisdn) {
        var msisdn = document.getElementById('WOrders_phone_contact').value;
        $.ajax({
            url: '<?=Yii::app()->controller->createUrl("package/checkKHDN");?>',
            dataType: 'json',
            method: 'POST',
            data: {
                'YII_CSRF_TOKEN': '<?php echo Yii::app()->request->csrfToken?>',
                msisdn: msisdn
            },
            success: function (resp) {
                if(resp.code == 1){
                    document.getElementById("promoCode").style.display = "none";
                    $("#modalKHDN").modal()
                    $('#mes').html('Số thuê bao thuộc tập KHDN');
                }
            },

        });

    }
</script>
