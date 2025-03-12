<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */
    /* @var $form CActiveForm */

?>
<div class="fillterarea form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
    )); ?>
    <?php
        if (!ADMIN && !SUPER_ADMIN && !USER_NOT_LOCATE) {
            if (isset(Yii::app()->user->sale_offices_id)) {
                $model->sale_office_code = Yii::app()->user->sale_offices_id;
            }
            if (isset(Yii::app()->user->brand_offices_id)) {
                $model->brand_offices_id = Yii::app()->user->brand_offices_id;
            }
            if (isset(Yii::app()->user->province_code)) {
                $model->province_code = Yii::app()->user->province_code;
            }
        }

    ?>
    <div class="col-sm-8">
        <div class="row">
            <div class="col-sm-5">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'start_date'); ?>
                    <div class="input-prepend input-group">
                        <span class="add-on input-group-addon">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        </span>
                        <?php echo $form->textField($model, 'start_date', array(
                            'class' => 'form-control',
                            'size' => 25,
                            'maxlength' => 50,
                            'autocomplete' => 'off'
                        )); ?>
                    </div>
                    <?php echo $form->error($model, 'start_date'); ?>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'end_date'); ?>
                    <div class="input-prepend input-group">
                        <span class="add-on input-group-addon">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        </span>
                        <?php echo $form->textField($model, 'end_date', array(
                            'class' => 'form-control',
                            'size' => 25,
                            'maxlength' => 50,
                            'autocomplete' => 'off'
                        )); ?>
                    </div>
                    <?php echo $form->error($model, 'end_date'); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-5">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'province_code'); ?>
                    <?php
                        $this->widget(
                            'booster.widgets.TbSelect2',
                            array(
                                'model'       => $model,
                                'attribute'   => 'province_code',
                                'data'        => AProvince::model()->getAllProvince(),
                                'htmlOptions' => array(
                                    'multiple' => FALSE,
                                    'prompt'   => (!ADMIN && !SUPER_ADMIN && !USER_NOT_LOCATE) ? NULL : Yii::t('report/menu', 'province_code'),
                                    'ajax'     => array(
                                        'type'   => 'POST',
                                        'url'    => Yii::app()->createUrl('aOrders/getSaleOfficeByProvince'), //or $this->createUrl('loadcities') if '$this' extends CController
                                        'update' => '#AOrders_sale_office_code',
                                        'data'   => array('province_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                    ),
                                    'onchange' => '$("#AOrders_sale_office_code").select2("val", "");
                                        $("#AOrders_brand_offices_id").html("");
                                        $("#AOrders_brand_offices_id").select2("val", "");
                                    ',
                                    //reset value selected
                                    'style'    => 'width:100%'
                                ),
                            )
                        );
                    ?>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">

                    <?php echo $form->labelEx($model, 'sale_office_code'); ?>
                    <?php
                        $this->widget(
                            'booster.widgets.TbSelect2',
                            array(
                                'model'       => $model,
                                'attribute'   => 'sale_office_code',
                                'data'        => ($model->province_code != '') ? ASaleOffices::model()->getSaleOfficesByProvince($model->province_code) : array(),
                                'htmlOptions' => array(
                                    'multiple' => FALSE,
                                    'prompt'   => (!ADMIN && !SUPER_ADMIN && !USER_NOT_LOCATE && Yii::app()->user->sale_offices_id != '') ? NULL : Yii::t('report/menu', 'sale_offices_id'),
                                    'ajax'     => array(
                                        'type'   => 'POST',
                                        'url'    => Yii::app()->createUrl('aOrders/getBrandOfficeBySaleCode'), //or $this->createUrl('loadcities') if '$this' extends CController
                                        'update' => '#AOrders_brand_offices_id',
                                        'data'   => array('sale_offices_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                    ),
                                    'onchange' => '$("#AOrders_brand_offices_id").select2("val", "");
                                    ',
                                    //reset value selected
                                    'style'    => 'width:100%'
                                ),
                            )
                        );
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'brand_offices_id'); ?>
                    <?php
                        $this->widget(
                            'booster.widgets.TbSelect2',
                            array(
                                'model'       => $model,
                                'attribute'   => 'brand_offices_id',
                                'data'        => ($model->sale_office_code != '') ? ABrandOffices::model()->getBrandOfficesBySaleCode($model->sale_office_code) : array(),
                                'htmlOptions' => array(
                                    'multiple' => FALSE,
                                    'prompt'   => (!ADMIN && !SUPER_ADMIN && !USER_NOT_LOCATE && isset(Yii::app()->user->brand_offices_id)) ? NULL : Yii::t('report/menu', 'brand_offices_id'),
                                    //reset value selected
                                    'style'    => 'width:100%'
                                ),
                            )
                        );
                    ?>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'delivery_type'); ?>
                    <?php echo $form->dropDownList($model, 'delivery_type', AOrders::getAllDeliveredType(), array(
                        'class' => 'form-control',
                        'empty' => 'Tất cả'
                    ))?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'period'); ?>
                    <?php echo $form->dropDownList($model, 'period', AOrders::getPeriodTime(), array(
                        'class' => 'form-control',
                        'empty' => 'Chọn thời gian còn lại'
                    )); ?>
                    <div class="period-error text-warning"></div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'status_shipper'); ?>
                    <?php echo $form->dropDownList($model, 'status_shipper', AOrders::getAllStatusReport(), array(
                            'class' => 'form-control',
                            'empty' => 'Tất cả',
                        )
                    ); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-5">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'channel'); ?>
                    <?php echo $form->dropDownList($model, 'channel',
                        array(
                            AOrders::CHANNEL_DLTC   => 'ĐLTC',
                            AOrders::CHANNEL_CTV    => 'CTV',
                        ),
                        array(
                            'class' => 'form-control',
                            'empty' => 'Tất cả',
                            'onchange' => 'checkChannel()'
                        )
                    ); ?>
                </div>
            </div>

            <div id="affiliate_source_dltc" class="col-sm-5 affiliate_source<?php echo ($model->channel == AOrders::CHANNEL_DLTC) ? '' : ' hidden'?>">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'affiliate_source'); ?>
                    <?php echo $form->dropDownList($model, 'affiliate_source',
                        AAffiliateManager::getListChannel(),
                        array(
                            'class' => 'form-control',
                            'empty' => 'Tất cả',
                        )
                    ); ?>
                </div>
            </div>

            <div id="affiliate_source_ctv" class="col-sm-5 affiliate_source <?php echo ($model->channel == AOrders::CHANNEL_CTV) ? '' : ' hidden'?>">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'promo_code'); ?>
                    <?php echo $form->textField($model, 'promo_code', array(
                        'class' => 'form-control'
                    ));?>
                </div>
            </div>
        </div>


            <div class="row">
                <div class="col-sm-5">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'is_pre_order'); ?>
                        <?php echo $form->dropDownList($model, 'is_pre_order',
                            array(
                                1 => 'Đơn hàng thông thường',
                                2 => 'Đơn hàng chọn số cặp đôi (đặt trước)',
                            ),
                            array(
                                'class' => 'form-control',
                                'empty' => 'Tất cả',
                            )
                        ); ?>
                    </div>
                </div>

                <div class="col-sm-2" style="margin-top: 20px;">
                    <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success')); ?>
                </div>

            </div>


    </div>

    <?php $this->endWidget(); ?>

</div>

<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/moment.min2.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datepicker/daterangepicker.js"></script>

<script type="text/javascript">

    function checkChannel(){
        var channel = $('#AOrders_channel').val();
        var filter_dtlc = $('#affiliate_source_dltc');
        var filter_ctv = $('#affiliate_source_ctv');

        if(channel == <?php echo AOrders::CHANNEL_DLTC?>){
            filter_dtlc.removeClass('hidden');
            filter_ctv.addClass('hidden');
        }else if(channel == <?php echo AOrders::CHANNEL_CTV?>){
            filter_ctv.removeClass('hidden');
            filter_dtlc.addClass('hidden');
        }else {
            filter_dtlc.addClass('hidden');
            filter_ctv.addClass('hidden');
        }
    }

    $(document).ready(function () {

        $('#AOrders_start_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
//            timePicker: true,
            timePickerIncrement: 5,
            format: 'DD/MM/YYYY',
            buttonClasses: ['btn btn-default'],
            applyClass: 'btn-small btn-primary',
            cancelClass: 'btn-small',
            locale: {
                applyLabel: 'Áp dụng',
                cancelLabel: 'Đóng',
                daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                firstDay: 1
            }
        }, function () {
        });

        $('#AOrders_end_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
//            timePicker: true,
            timePickerIncrement: 5,
            format: 'DD/MM/YYYY',
            buttonClasses: ['btn btn-default'],
            applyClass: 'btn-small btn-primary',
            cancelClass: 'btn-small',
            locale: {
                applyLabel: 'Áp dụng',
                cancelLabel: 'Đóng',
                daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                firstDay: 1
            }
        }, function () {
        });

        if ($('#AOrders_period').val() != '') {
            $('.period-error').html("Kết quả lấy mốc tại thời điểm hiện tại");
            $('.period-error').css("margin-top", "10px");
            $('.period-error').css("margin-left", "2px");
        }
        $('#AOrders_period').change(function () {
            if (this.value != '') {
                $('.period-error').html("Kết quả lấy mốc tại thời điểm hiện tại");
                $('.period-error').css("margin-top", "10px");
                $('.period-error').css("margin-left", "2px");
            } else {
                $('.period-error').html("");
            }
        });

    });

</script>
