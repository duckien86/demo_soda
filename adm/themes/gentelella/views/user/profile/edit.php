<?php
    $this->pageTitle   = Yii::app()->name . ' - ' . UserModule::t("Profile");
    $this->breadcrumbs = array(
        UserModule::t("Profile") => array('profile'),
        UserModule::t("Edit"),
    );
?>
<div class="x_panel container-fluid">
    <div class="x_content">
        <?php if (Yii::app()->user->hasFlash('profileMessage')): ?>
            <div class="alert alert-success alert-dismissible fade in">
                <button aria-label="<?php echo Yii::t('app', 'Close') ?>" data-dismiss="alert" class="close"
                        type="button"><span aria-hidden="true">×</span></button>
                <?php echo Yii::app()->user->getFlash('profileMessage'); ?>
            </div>
        <?php endif; ?>
        <div class="form">
            <?php
                $form = $this->beginWidget('UActiveForm', array(
                    'id'                   => 'profile-form',
                    'enableAjaxValidation' => TRUE,
                    'htmlOptions'          => array(
                        'class'   => 'form-horizontal form-label-left',
                        'enctype' => 'multipart/form-data',
                    ),
                ));
            ?>

            <span
                    class="section"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></span>

            <?php echo $form->errorSummary(array($model, $profile)); ?>

            <?php
                $profileFields = $profile->getFields();
                if ($profileFields) {
                    foreach ($profileFields as $field) {
                        ?>
                        <div class="form-group">
                            <?php
                                echo $form->labelEx($profile, $field->varname, array('class' => 'control-label col-md-3 col-sm-3 col-xs-12'));

                                echo '<div class="col-md-6 col-sm-6 col-xs-12">';
                                if ($field->widgetEdit($profile)) {
                                    ?>
                                    <div class="form-group">
                                        <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </span>
                                            <?php
                                                if ($profile->birthday == '') {
                                                    $profile->birthday = date('Y-m-d');
                                                }
                                                echo $form->textField($profile, 'birthday', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                } elseif ($field->range) {
                                    echo $form->dropDownList($profile, $field->varname, Profile::range($field->range));
                                } elseif ($field->field_type == "TEXT") {
                                    echo $form->textArea($profile, $field->varname, array('class' => 'resizable_textarea form-control', 'rows' => 6, 'cols' => 50));
                                } else {
                                    echo $form->textField($profile, $field->varname, array('class' => 'textbox', 'maxlength' => (($field->field_size) ? $field->field_size : 255)));
                                }
                                echo '<ul class="parsley-errors-list"><li>' . $form->error($profile, $field->varname, array('class' => 'parsley-required')) . '</li></ul>';
                                echo '</div>';
                            ?>
                        </div>
                        <?php
                    }
                }
            ?>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'email', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo $form->textField($model, 'email', array('class' => 'textbox', 'maxlength' => 128)); ?>
                    <ul class="parsley-errors-list">
                        <li><?php echo $form->error($model, 'email', array('class' => 'parsley-required')); ?></li>
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12 required" for="User_phone">Số Eload <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo $form->textField($model, 'phone', array('class' => 'textbox', 'maxlength' => 128)); ?>
                    <ul class="parsley-errors-list">
                        <li><?php echo $form->error($model, 'phone', array('class' => 'parsley-required')); ?></li>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'phone_2', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')); ?>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo $form->textField($model, 'phone_2', array('class' => 'textbox', 'maxlength' => 128)); ?>
                    <ul class="parsley-errors-list">
                        <li><?php echo $form->error($model, 'phone_2', array('class' => 'parsley-required')); ?></li>
                    </ul>
                </div>
            </div>

            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <span class="btnintbl">
                                <span class="icondk">
                                    <?php echo CHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save')); ?>
                                </span>
                            </span>
                </div>
            </div>

            <?php $this->endWidget(); ?>

        </div>
        <!-- form -->
    </div>
</div>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/moment.min2.js"></script>
<script type="text/javascript"
        src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datepicker/daterangepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#Profile_birthday').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
//            timePicker: true,
            timePickerIncrement: 5,
            format: 'YYYY-MM-DD',
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