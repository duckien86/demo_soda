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
            <td><?php echo $form->label($model, 'sim_freedoo'); ?>:</td>
            <td>
                <?php echo $form->dropDownList($model, 'sim_freedoo', $model->getFreedooType(), array(
                    'empty' => 'Tất cả',
                    'class' => 'dropdownlist form-control',
                )); ?>
            </td>
            <td><?php echo $form->label($model, 'price_card'); ?>:</td>
            <td>
                <?php echo $form->dropDownList($model, 'price_card', $model->getPriceCard(), array(
                    'empty' => 'Tất cả',
                    'class' => 'dropdownlist form-control',
                )); ?>
            </td>

        </tr>
        <tr>
            <td><?php echo $form->label($model, 'card_type'); ?>:</td>
            <td>
                <?php echo $form->dropDownList($model, 'card_type', $model->getCardType(), array(
                    'empty' => 'Tất cả',
                    'class' => 'dropdownlist form-control',
                )); ?>
            </td>
            <td><?php echo $form->label($model, 'payment_method'); ?>:</td>
            <td><?php
                    echo CHtml::activeDropDownList(
                        $model,
                        'payment_method',
                        $model->getAllPaymentMethod(),
                        array('empty' => Yii::t('report/menu', 'payment_method'), 'class' => 'dropdownlist form-control')
                    )
                ?>
            </td>

        </tr>
        <tr>
            <td>
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-warning')); ?>
            </td>
<!--            <td>-->
<!--                --><?php //if ($model->on_detail == 'on') { ?>
<!--                    <input type="checkbox" name="ReportForm[on_detail]" checked="checked"> Hiện thị chi tiết<br>-->
<!--                --><?php //} else { ?>
<!--                    <input type="checkbox" name="ReportForm[on_detail]"> Hiện thị chi tiết<br>-->
<!--                --><?php //} ?>
<!--            </td>-->
        </tr>
        </tr>
    </table>

    <?php $this->endWidget(); ?>
</div>
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
    });

</script>