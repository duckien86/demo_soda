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
                    <?php echo $form->labelEx($model, 'status'); ?>
                    <?php echo $form->dropDownList($model, 'status_fiber', AOrders::getAllStatusFiber(), array(
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
                    <label>Loại gói cước</label>
                    <?php echo $form->dropDownList($model, 'type_package', AOrders::getAllTypePackage(), array(
                        'class' => 'form-control',
                    ))?>
                </div>
            </div>
            <div class="col-sm-5">

            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">

            </div>
            <div class="col-sm-5">

            </div>
        </div>

        <div class="row">
            <div class="col-sm-5">

            </div>

        </div>


        <div class="row">
            <div class="col-sm-5">
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
