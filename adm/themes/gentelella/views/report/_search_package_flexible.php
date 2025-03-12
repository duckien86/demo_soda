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
    <table>
        <tr>
            <td><?php echo $form->label($model, 'start_date'); ?>:</td>
            <td>
                <div class="input-prepend input-group">
                    <span class="add-on input-group-addon">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                    </span>
                    <?php echo $form->textField($model, 'start_date', array(
                        'class' => 'form-control',
                        'size' => 35,
                        'maxlength' => 50,
                        'autocomplete' => 'off'
                    )); ?>
                </div>
                <?php echo $form->error($form_validate, 'start_date'); ?>
            </td>
            <td><?php echo $form->label($model, 'end_date'); ?>:</td>
            <td>
                <div class="input-prepend input-group">
                    <span class="add-on input-group-addon">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                    </span>
                    <?php echo $form->textField($model, 'end_date', array(
                        'class' => 'form-control',
                        'size' => 35,
                        'maxlength' => 50,
                        'autocomplete' => 'off'
                    )); ?>
                </div>
                <?php echo $form->error($form_validate, 'end_date'); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->label($model, 'period'); ?>:</td>
            <td><?php echo $form->dropDownList(
                    $model,
                    'period',
                    array('' => 'Chu kỳ', ReportForm::DAY_FLEXIBLE => 'Ngày', ReportForm::MONTH_FLEXIBLE => 'Tháng'),
                    array('class' => 'dropdownlist form-control',
                          'ajax'  => array(
                              'type'   => 'POST',
                              'url'    => Yii::app()->createUrl('report/getPackageByPeriodFlexible'), //or $this->createUrl('loadcities') if '$this' extends CController
                              'update' => '#ReportForm_package_id',
                              'data'   => array('period' => 'js:this.value', 'package_group' => 'js:document.getElementById("ReportForm_package_id").value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                          ),
                    )
                );
                ?>
            </td>
            <td><?php echo $form->label($model, 'package_group'); ?>:</td>
            <td>
                <?php echo $form->dropDownList($model, 'package_group'
                    , $model->getPackageGroupFlexible(), array(
                        'empty' => Yii::t('report/menu', 'package_group'),
                        'ajax'  => array(
                            'type'   => 'POST',
                            'url'    => Yii::app()->createUrl('report/getPackageByGroupFlexible'), //or $this->createUrl('loadcities') if '$this' extends CController
                            'update' => '#ReportForm_package_id',
                            'data'   => array('package_group' => 'js:this.value', 'period' => 'js:document.getElementById("ReportForm_period").value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                        ),
                        'class' => 'dropdownlist form-control',
                    )); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->label($model, 'package_id'); ?>:</td>
            <td><?php echo $form->dropDownList(
                    $model,
                    'package_id',
                    ($model->package_group != '' || $model->period != '') ? $model->getPackageByGroupFlexible($model->package_group, $model->period) : $model->getAllPackageFlexible(),
                    array('empty' => Yii::t('report/menu', 'package_id'),
                          'class' => 'dropdownlist form-control',
                    )
                )
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-warning')); ?>
            </td>
        </tr>
    </table>

    <?php $this->endWidget(); ?>
</div>
<style>
    #ReportForm_input_type label {
        margin-right: 15px;
    }

    #ReportForm_offices_id {
        font-size: 11px;
    }

</style>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/moment.min2.js"></script>
<script type="text/javascript"
        src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datepicker/daterangepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $('#ReportForm_start_date').daterangepicker({
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

        $('#ReportForm_end_date').daterangepicker({
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

        $('#ReportForm_end_date').daterangepicker({
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
        if ($('input[name="ReportForm[input_type]"]:checked').val() == 1) {
            $('.brand_offices').css("display", "none");
        } else if ($('input[name="ReportForm[input_type]"]:checked').val() == 2) {
            $('.brand_offices').css("display", "block");
        } else {
            $('.brand_offices').css("display", "none");
        }
        $('input:radio[name="ReportForm[input_type]"]').change(function () {
            var radio_check = $(this).val();
            if (radio_check == 2) {
                $('.brand_offices').css("display", "block");
            } else {
                $('.brand_offices').css("display", "none");
            }

        });
    });

</script>