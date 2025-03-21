<?php
    /* @var $this ReportController */
    /* @var $model ReportForm */
    /* @var $form CActiveForm */
    if (!ADMIN && !SUPER_ADMIN && !USER_NOT_LOCATE) {
        if (isset(Yii::app()->user->sale_offices_id)) {
            if (!empty(Yii::app()->user->sale_offices_id)) {
                $model->sale_office_code = Yii::app()->user->sale_offices_id;
            }
        }
        if (isset(Yii::app()->user->brand_offices_id)) {
            if (!empty(Yii::app()->user->brand_offices_id)) {
                $model->brand_offices_id = Yii::app()->user->brand_offices_id;
            }
        }
        if (isset(Yii::app()->user->province_code)) {
            $model->province_code = Yii::app()->user->province_code;
        }
    }
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
                        'size' => 25,
                        'maxlength' => 50,
                        'autocomplete' => 'off'
                    )); ?>
                </div>
                <?php echo $form->error($model, 'start_date'); ?>
            </td>
            <td><?php echo $form->label($model, 'end_date'); ?>:</td>
            <td>
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
            </td>
        </tr>
        <tr>
            <td><?php echo $form->label($model, 'province_code'); ?>:</td>
            <td>
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
                                'style'    => 'width:100%'
                            ),
                        )
                    );
                ?>
            </td>
            <td><?php echo $form->label($model, 'order_id'); ?>:</td>
            <td>
                <?php echo $form->textField($model,'order_id', array(
                    'class' => 'form-control'
                )); ?>
            </td>
        </tr>
        <tr>

            <td><?php echo $form->label($model, 'msisdn'); ?>:</td>
            <td>
                <?php echo $form->textField($model,'msisdn', array(
                    'class' => 'form-control'
                )); ?>
            </td>
        </tr>

        <tr>
            <td>
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-warning')); ?>
            </td>
            <td>
                <?php echo $form->checkBox($model, 'on_detail', array(
                    'value' => 'on',
                )); ?>
                <?php echo $form->label($model, 'on_detail'); ?>
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
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datepicker/daterangepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $('#ReportZalo_start_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
//            timePicker: true,
            timePickerIncrement: 5,
            format: 'DD/MM/YYYY ',
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

        $('#ReportZalo_end_date').daterangepicker({
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

    });

</script>