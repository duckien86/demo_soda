<?php
    /* @var $this ACampaignConfigsController */
    /* @var $model ACampaignConfigs */
    /* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'                   => 'acampaign-configs-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => FALSE,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">

    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'utm_source'); ?>
                <?php echo $form->textField($model, 'utm_source', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'utm_source'); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'utm_campaign'); ?>
                <?php echo $form->textField($model, 'utm_campaign', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'utm_campaign'); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'target_link'); ?>
                <?php echo $form->textField($model, 'target_link', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'target_link'); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'type'); ?>
                <?php echo $form->textField($model, 'type', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'type'); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'utm_medium'); ?>
                <?php echo $form->textField($model, 'utm_medium', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'utm_medium'); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'create_date'); ?>
                <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </span>
                    <?php
                        $model->create_date = ($model->isNewRecord) ? date('d/m/Y') : date('d/m/Y', strtotime($model->create_date));
                        echo $form->textField($model, 'create_date', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                    ?>
                </div>
                <?php echo $form->error($model, 'create_date'); ?>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox-nopad">
                    <label>
                        <?php
                            if ($model->isNewRecord) {
                                echo $form->checkBox($model, 'status', array('checked' => 'checked', 'class' => 'flat')) . ' ' . Yii::t('adm/label', 'active');
                            } else {
                                echo $form->checkBox($model, 'status', array('class' => 'flat')) . ' ' . Yii::t('adm/label', 'active');
                            }
                        ?>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Tạo mới' : 'Cập nhật', array('class' => 'btn btn-warning')); ?>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/moment.min2.js"></script>
<script type="text/javascript"
        src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datepicker/daterangepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#ACampaignConfigs_create_date').daterangepicker({
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