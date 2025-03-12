<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">

                <?php /** @var BootActiveForm $form */
                    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id'   => 'searchForm',
                        'type' => 'horizontal',
                    )); ?>
                <div class="row search-form-publisher">
                    <div class="col-md-1 col-xs-6 label-report-social">
                        <?= $form->label($model, 'start_date'); ?>
                    </div>
                    <div class="col-md-4 col-xs-6">
                        <div class="form-group">
                            <div class="input-prepend input-group">
                                <?php
                                    echo $form->textField($model, 'start_date', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                                ?>
                                <span class="add-on input-group-addon">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </span>
                            </div>
                            <?php echo $form->error($form_validate, 'start_date'); ?>
                        </div>
                    </div>
                </div>
                <div class="row search-form-publisher">
                    <div class="col-md-1 col-xs-6 label-report-social">
                        <?= $form->label($model, 'end_date'); ?>
                    </div>
                    <div class="col-md-4 col-xs-6">
                        <div class="form-group">
                            <div class="input-prepend input-group">
                                <?php
                                    echo $form->textField($model, 'end_date', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                                ?>
                                <span class="add-on input-group-addon">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </span>
                            </div>
                            <?php echo $form->error($form_validate, 'end_date'); ?>
                        </div>
                    </div>
                </div>
                <div class="row search-form-publisher">
                    <div class="col-md-1 col-xs-6 label-report-social">
                        <?= $form->label($model, 'status'); ?>
                    </div>
                    <div class="col-md-4 col-xs-6">
                        <div class="form-group">
                            <?php
                                echo CHtml::activeDropDownList(
                                    $model,
                                    'status',
                                    $model->getAllStatus(),
                                    array('empty' => 'Chọn tất cả', 'class' => 'dropdownlist form-control')
                                )
                            ?>
                        </div>
                        <?php echo $form->error($form_validate, 'status'); ?>
                    </div>
                </div>
                <div class="row search-form-publisher">
                    <div class="col-md-1 col-xs-6"></div>
                    <div class="col-md-2 col-xs-6">
                        <?php $this->widget('booster.widgets.TbButton', array('buttonType' => 'submit', 'label' => 'Xem kết quả', 'context' => 'default')); ?>
                    </div>
                </div>
            </div>

            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>

</div>

<style>
    .btn span.glyphicon {
        opacity: 0;
    }

    .btn.active span.glyphicon {
        opacity: 1;
    }

</style>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/moment.min2.js"></script>
<script type="text/javascript"
        src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datepicker/daterangepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $('#AReportSocialForm_start_date').daterangepicker({
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

        $('#AReportSocialForm_end_date').daterangepicker({
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