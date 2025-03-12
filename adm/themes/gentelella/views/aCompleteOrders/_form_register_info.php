<?php if ($order_id): ?>
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo Yii::t('adm/label', 'register_info') ?></h2>

            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id'                   => 'register-info-form',
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // There is a call to performAjaxValidation() commented in generated controller code.
                // See class documentation of CActiveForm for details on this.
                'enableAjaxValidation' => TRUE,
                'htmlOptions'          => array('enctype' => 'multipart/form-data'),
            )); ?>
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <?php echo $form->labelEx($complete_form, 'nation', array('class' => 'col-md-6 no_pad')); ?>
                        <?php
                            $this->widget(
                                'booster.widgets.TbSelect2',
                                array(
                                    'model'       => $complete_form,
                                    'attribute'   => 'nation',
                                    'data'        => $national,
                                    'value'       => $complete_form->nation,
                                    'htmlOptions' => array(
                                        'multiple' => FALSE,
                                        'style'    => 'width:100%;',
                                        'prompt'   => Yii::t('adm/label', 'Chọn quốc tịch'),
                                    ),
                                )
                            ); ?>
                        <?php echo $form->error($complete_form_validate, 'nation'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($complete_form, 'customer_type', array('class' => 'col-md-6 no_pad')); ?>
                        <?php echo $form->dropdownlist($complete_form, 'customer_type', $customer_type, array('class' => 'textbox', 'maxlength' => 255)); ?>
                        <?php echo $form->error($complete_form_validate, 'customer_type'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($complete_form, 'full_name', array('class' => 'col-md-6 no_pad')); ?>
                        <?php echo $form->textField($complete_form, 'full_name', array('class' => 'textbox', 'maxlength' => 255)); ?>
                        <?php echo $form->error($complete_form_validate, 'full_name'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($complete_form, 'birth_day'); ?>
                        <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </span>
                            <?php
                                $complete_form->birth_day = ($complete_form->birth_day == '') ? '' : date('d/m/Y', strtotime($complete_form->birth_day));
                                echo $form->textField($complete_form, 'birth_day', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                            ?>
                        </div>
                        <?php echo $form->error($complete_form_validate, 'birth_day'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($complete_form, 'gender'); ?>
                        <div class="checkbox-nopad">
                            <label>
                                <?php
                                    $accountStatus = array(1 => 'Nam', 2 => 'Nữ');
                                    echo $form->radioButtonList($complete_form, 'gender', $accountStatus, array('separator' => ' '));
                                ?>
                                &nbsp;&nbsp;&nbsp;</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($complete_form, 'personal_id_type', array('class' => 'col-md-6 no_pad')); ?>
                        <?php echo $form->dropdownlist($complete_form, 'personal_id_type', ($post == 1) ? array() : $personal_id_type, array('class' => 'textbox', 'maxlength' => 255, 'readOnly' => ($post == 1) ? TRUE : FALSE)); ?>
                        <?php echo $form->error($complete_form_validate, 'personal_id_type'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($complete_form, 'number_page', array('class' => 'col-md-6 no_pad')); ?>
                        <?php echo $form->textField($complete_form, 'number_page', array('class' => 'textbox', 'maxlength' => 255)); ?>
                        <?php echo $form->error($complete_form_validate, 'number_page'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($complete_form, 'personal_id_create_date'); ?>
                        <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </span>
                            <?php
                                $complete_form->personal_id_create_date = ($complete_form->personal_id_create_date == '') ? '' : date('d/m/Y', strtotime($complete_form->personal_id_create_date));
                                echo $form->textField($complete_form, 'personal_id_create_date', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                            ?>
                        </div>
                        <?php echo $form->error($complete_form, 'personal_id_create_date'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($complete_form, 'personal_id_create_place', array('class' => 'col-md-6 no_pad')); ?>
                        <?php
                            $this->widget(
                                'booster.widgets.TbSelect2',
                                array(
                                    'model'       => $complete_form,
                                    'attribute'   => 'personal_id_create_place',
                                    'data'        => $provinces,
                                    'value'       => $complete_form->personal_id_create_place,
                                    'htmlOptions' => array(
                                        'multiple' => FALSE,
                                        'style'    => 'width:100%;',
                                        'prompt'   => Yii::t('adm/label', 'Chọn cơ quan cấp'),
                                    ),
                                )
                            ); ?>
                        <?php echo $form->error($complete_form_validate, 'personal_id_create_place'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($complete_form, 'subscription_permanent_address', array('class' => 'col-md-6 no_pad')); ?>
                        <?php echo $form->textField($complete_form, 'subscription_permanent_address', array('class' => 'textbox', 'maxlength' => 255)); ?>
                        <?php echo $form->error($complete_form_validate, 'subscription_permanent_address'); ?>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">

                    <div class="form-group">
                        <?php echo $form->labelEx($complete_form, 'phone_number', array('class' => 'col-md-6 no_pad')); ?>
                        <?php echo $form->textField($complete_form, 'phone_number', array('class' => 'textbox', 'maxlength' => 255, 'readOnly' => TRUE)); ?>
                        <?php echo $form->error($complete_form_validate, 'phone_number'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($complete_form, 'package_code', array('class' => 'col-md-6 no_pad')); ?>
                        <?php echo $form->textField($complete_form, 'package_code', array('class' => 'textbox', 'maxlength' => 255, 'readOnly' => TRUE)); ?>
                        <?php echo $form->error($complete_form_validate, 'package_code'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($complete_form, 'sim', array('class' => 'col-md-6 no_pad')); ?>
                        <?php echo $form->textField($complete_form, 'sim', array('class' => 'textbox', 'maxlength' => 255, 'placeholder' => 'Nhập 5 số cuối của số Serial Sim')); ?>
                        <?php echo $form->error($complete_form_validate, 'sim'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($complete_form, 'register_for', array('class' => 'col-md-6 no_pad')); ?>
                        <?php echo $form->dropdownlist($complete_form, 'register_for', $complete_form->getRegisterFor(), array('class' => 'textbox', 'maxlength' => 255)); ?>
                        <?php echo $form->error($complete_form_validate, 'register_for'); ?>
                    </div>

                    <?php if(isset($sim) && !empty($sim->esim_qrcode)){?>
                        <div class="form-group">
                            <label class="col-sm-12 no_pad">eSim QR CODE</label>
                            <?php $this->renderPartial('_popup_esim_qrcode', array(
                                'order'     => $order,
                                'sim'       => $sim,
                                'package'   => $package,
                                'shipper'   => $shipper,
                                'user'      => $user,
                                'modal'     => true,
                            ));?>
                        </div>
                    <?php } ?>

                </div>
            </div>

            <div class="row buttons" style="margin-left: 0px;">
                <?php echo CHtml::submitButton('Tiếp tục', array('class' => 'btn btn-success')); ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
<?php endif; ?>
<style>
    #ACustomerForm_sim::placeholder {
        color: darkgray;
    }
</style>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/moment.min2.js"></script>
<script type="text/javascript"
        src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datepicker/daterangepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#ACustomerForm_birth_day').daterangepicker({
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
        $('#ACustomerForm_personal_id_create_date').daterangepicker({
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