<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */
    /* @var $form CActiveForm */
?>
<div class="fillterarea form">
    <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id'          => 'renueve-detail-form',
//            'action'      => Yii::app()->createAbsoluteUrl("cskhShipper/view", array('id' => $model->id)),
            //'enableAjaxValidation' => true,
            'htmlOptions' => array('onsubmit' => "return false;"),
        )); ?>
    <input type="hidden" value="<?= $model->id ?>" name="AShipper[id]">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'start_date'); ?>
                    <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </span>
                        <input class="form-control active" size="25" maxlength="50" name="start_date"
                               id="AShipper_start_date" type="text">
                    </div>
                    <?php echo $form->error($model, 'start_date'); ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'end_date'); ?>
                    <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </span>
                        <input class="form-control active" size="25" maxlength="50" name="end_date"
                               id="AShipper_end_date" type="text">
                    </div>
                    <?php echo $form->error($model, 'end_date'); ?>
                </div>
            </div>

            <div class="col-md-2" style="margin-top: 25px;">
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success')); ?>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/moment.min2.js"></script>
<script type="text/javascript"
        src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datepicker/daterangepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $('#AShipper_start_date').daterangepicker({
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

        $('#AShipper_end_date').daterangepicker({
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