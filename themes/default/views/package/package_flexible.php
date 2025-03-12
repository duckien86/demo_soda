<?php
    /* @var $this PackageController */
    /* @var $modelPackage WPackage */
    /* @var $modelOrder WOrders */
    /* @var $form CActiveForm */
    /* @var $package_flexible */
?>
<script src='https://www.google.com/recaptcha/api.js?hl=vi'></script>
<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <section class="ss-bg">
        <div class="container no_pad_xs">
            <div class="checkout-process">
                <div class="col-md-4 col-md-push-8 no_pad_xs">
                    <div id="main_right_section">
                        <?php $this->renderPartial('_panel_order_flexible', array(
                            'modelOrder'       => $modelOrder,
                            'modelPackage'     => $modelPackage,
                            'package_flexible' => $package_flexible,
                        )); ?>
                    </div>
                </div>
                <div class="col-md-8 col-md-pull-4 no_pad_xs">
                    <div id="main_left_section">
                        <div class="ss-box1-right-title">
                            <div class="ss-box1-left-top-tit">
                                <span class="uppercase">Gói cước linh hoạt</span>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="space_10"></div>

                        <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                            'id'                   => 'package_flexible',
                            'action'               => Yii::app()->controller->createUrl('package/packageFlexible'),
                            'enableAjaxValidation' => TRUE,
                        )); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <?php echo CHtml::label(Yii::t('web/portal', 'input_phone_contact'), 'WOrders_phone_contact', array('class' => 'label_text')) ?>
                            </div>
                            <div class="col-md-8">
                                <?php echo $form->textField($modelOrder, 'phone_contact', array(
                                    'class'     => 'textbox',
                                    'maxlength' => 255,
                                    'onchange'  => 'getOrderFlexible();'
                                )); ?>
                            </div>
                            <?php echo $form->error($modelOrder, 'phone_contact'); ?>
                        </div>
                        <div class="space_10"></div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <?php echo CHtml::label('Chọn chu kỳ', 'WPackage_period', array('class' => 'label_text')) ?>
                            </div>
                            <div class="col-md-8">
                                <?php
                                    echo $form->dropDownList($modelPackage, 'period', array(
                                        WPackage::PERIOD_1  => 'Ngày',
                                        WPackage::PERIOD_30 => 'Tháng',
                                    ), array('class' => 'dropdownlist', 'onChange' => 'getListPackByAjax(this.value);'));
                                ?>
                            </div>
                        </div>
                        <div class="space_20"></div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <?php echo CHtml::label(Yii::t('web/portal', 'captcha'), 'WOrders_captcha', array('class' => 'label_text')) ?>
                            </div>
                            <div class="col-md-8">
                                <div id="captcha_place_holder"
                                     class="g-recaptcha"
                                     data-sitekey="6Lcr1B8aAAAAAHfl5_cIZIIpy3a0hU5NGf4znMyO"></div>
									 
                                <?php echo $form->error($modelOrder, 'captcha'); ?>
                            </div>
                        </div>
                        <div class="space_20"></div>
                        <div class="form-group">
                            <?php
                                //                                if ($modelOrder->hasErrors() && empty($packages)):
                                if ($modelOrder->hasErrors()):
                                    ?>
                                    <div class="help-block error">Chọn các gói cước để đăng ký</div>
                                <?php endif; ?>
                        </div>
                        <?php
                            $month = 'display:none;';
                            $day   = '';
                            if ($modelPackage->period == WPackage::PERIOD_30) {
                                $month = '';
                                $day   = 'display:none;';
                            }
                        ?>
                        <div id="box_package_day" style="<?= $day ?>">
                            <?php $this->renderPartial('_package_day', array(
                                'modelOrder' => $modelOrder,
                            )); ?>
                        </div>
                        <div id="box_package_month" style="<?= $month ?>">
                            <?php $this->renderPartial('_package_month', array(
                                'modelOrder' => $modelOrder,
                            )); ?>
                        </div>
                        <div class="space_10"></div>
                        <div class="text-center">
                            <?php echo CHtml::submitButton(Yii::t('web/portal', 'register'), array('class' => 'btn btn_green')); ?>
                        </div>
                        <?php $this->endWidget(); ?>
                        <div class="space_60"></div>
                    </div>
                </div>
            </div>
    </section>
    <!--end section #ss-bg -->
</div>

<script>
    $('#pack_call_int').find('label').each(function (index) {
        var selected_index = 0;
        var max_length_label = $('#pack_call_int').find('label').length;
        var input = $(this).find('input.chk_package');
        input.on('change', function () {
            if ($(this).is(":checked")) {
                selected_index = index;

                $('#pack_call_int').find('label').each(function (index) {
                    if (index <= selected_index) {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_active');
                    } else {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_disable');
                    }
                });
            } else {
                $('#pack_call_int').find('label').each(function (index) {
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                });
            }
        });
    });

    $('#pack_call_ext').find('label').each(function (index) {
        var selected_index = 0;
        var max_length_label = $('#pack_call_ext').find('label').length;
        var input = $(this).find('input.chk_package');
        input.on('change', function () {
            if ($(this).is(":checked")) {
                selected_index = index;

                $('#pack_call_ext').find('label').each(function (index) {
                    if (index <= selected_index) {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_active');
                    } else {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_disable');
                    }
                });
            } else {
                $('#pack_call_ext').find('label').each(function (index) {
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                });
            }
        });
    });

    $('#pack_sms_int').find('label').each(function (index) {
        var selected_index = 0;
        var max_length_label = $('#pack_sms_int').find('label').length;
        var input = $(this).find('input.chk_package');
        input.on('change', function () {
            if ($(this).is(":checked")) {
                selected_index = index;

                $('#pack_sms_int').find('label').each(function (index) {
                    if (index <= selected_index) {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_active');
                    } else {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_disable');
                    }
                });
            } else {
                $('#pack_sms_int').find('label').each(function (index) {
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                });
            }
        });
    });

    $('#pack_sms_ext').find('label').each(function (index) {
        var selected_index = 0;
        var max_length_label = $('#pack_sms_ext').find('label').length;
        var input = $(this).find('input.chk_package');
        input.on('change', function () {
            if ($(this).is(":checked")) {
                selected_index = index;

                $('#pack_sms_ext').find('label').each(function (index) {
                    if (index <= selected_index) {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_active');
                    } else {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_disable');
                    }
                });
            } else {
                $('#pack_sms_ext').find('label').each(function (index) {
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                });
            }
        });
    });

    $('#pack_data').find('label').each(function (index) {
        var selected_index = 0;
        var max_length_label = $('#pack_data').find('label').length;
        var input = $(this).find('input.chk_package');
        input.on('change', function () {
            if ($(this).is(":checked")) {
                selected_index = index;

                $('#pack_data').find('label').each(function (index) {
                    if (index <= selected_index) {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_active');
                    } else {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_disable');
                    }
                });
            } else {
                $('#pack_data').find('label').each(function (index) {
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                });
            }
        });
    });

    $('#pack_call_int_month').find('label').each(function (index) {
        var selected_index = 0;
        var max_length_label = $('#pack_call_int_month').find('label').length;
        var input = $(this).find('input.chk_package');
        input.on('change', function () {
            if ($(this).is(":checked")) {
                selected_index = index;

                $('#pack_call_int_month').find('label').each(function (index) {
                    if (index <= selected_index) {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_active');
                    } else {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_disable');
                    }
                });
            } else {
                $('#pack_call_int_month').find('label').each(function (index) {
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                });
            }
        });
    });

    $('#pack_call_ext_month').find('label').each(function (index) {
        var selected_index = 0;
        var max_length_label = $('#pack_call_ext_month').find('label').length;
        var input = $(this).find('input.chk_package');
        input.on('change', function () {
            if ($(this).is(":checked")) {
                selected_index = index;

                $('#pack_call_ext_month').find('label').each(function (index) {
                    if (index <= selected_index) {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_active');
                    } else {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_disable');
                    }
                });
            } else {
                $('#pack_call_ext_month').find('label').each(function (index) {
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                });
            }
        });
    });

    $('#pack_sms_int_month').find('label').each(function (index) {
        var selected_index = 0;
        var max_length_label = $('#pack_sms_int_month').find('label').length;
        var input = $(this).find('input.chk_package');
        input.on('change', function () {
            if ($(this).is(":checked")) {
                selected_index = index;

                $('#pack_sms_int_month').find('label').each(function (index) {
                    if (index <= selected_index) {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_active');
                    } else {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_disable');
                    }
                });
            } else {
                $('#pack_sms_int_month').find('label').each(function (index) {
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                });
            }
        });
    });

    $('#pack_sms_ext_month').find('label').each(function (index) {
        var selected_index = 0;
        var max_length_label = $('#pack_sms_ext_month').find('label').length;
        var input = $(this).find('input.chk_package');
        input.on('change', function () {
            if ($(this).is(":checked")) {
                selected_index = index;

                $('#pack_sms_ext_month').find('label').each(function (index) {
                    if (index <= selected_index) {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_active');
                    } else {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_disable');
                    }
                });
            } else {
                $('#pack_sms_ext_month').find('label').each(function (index) {
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                });
            }
        });
    });

    $('#pack_data_month').find('label').each(function (index) {
        var selected_index = 0;
        var max_length_label = $('#pack_data_month').find('label').length;
        var input = $(this).find('input.chk_package');
        input.on('change', function () {
            if ($(this).is(":checked")) {
                selected_index = index;

                $('#pack_data_month').find('label').each(function (index) {
                    if (index <= selected_index) {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_active');
                    } else {
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                        $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                        $(this).children('.progress').children('.progress-bar').addClass('bg_disable');
                    }
                });
            } else {
                $('#pack_data_month').find('label').each(function (index) {
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_active');
                    $(this).children('.progress').children('.progress-bar').removeClass('bg_disable');
                });
            }
        });
    });


    //select only one checkbox in group
    $('input.chk_package').on('change', function () {
        var $box = $(this);
        var group = $(this).parent().attr('id');
        if ($box.is(":checked")) {
            $('#' + group + ' input.chk_package').prop('checked', false);
            $box.prop("checked", true);
        } else {
            $box.prop("checked", false);
        }
        getOrderFlexible();
    });

    function getListPackByAjax(package_type) {
        //uncheck and remove class
        $('input[type=checkbox]').prop("checked", false);
        $('.progress-bar').removeClass('bg_active bg_disable');

        $('#order_amount').text(0);
        $('#order_total_amount').text(0);
        if (package_type == '<?=WPackage::PERIOD_1?>') {
            $('#box_package_day').css('display', 'block');
            $('#box_package_month').css('display', 'none');
        } else {
            $('#box_package_day').css('display', 'none');
            $('#box_package_month').css('display', 'block');
        }
        getOrderFlexible();//reset order info
    }

    function getOrderFlexible() {
        var form_data = new FormData(document.getElementById("package_flexible"));//formID
        $.ajax({
            type: "POST",
            url: "<?=Yii::app()->controller->createUrl('package/getOrderPackageFlexible');?>",
            crossDomain: true,
            dataType: 'json',
            data: form_data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            success: function (result) {
                $('#order_flexible_table').html(result.content);
            }
        });
    }
</script>