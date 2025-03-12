<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */
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
                        'size' => 25,
                        'maxlength' => 50,
                        'autocomplete' => 'off'
                    )); ?>
                </div>
                <?php echo $form->error($form_validate, 'end_date'); ?>
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
                                'ajax'     => array(
                                    'type'   => 'POST',
                                    'url'    => Yii::app()->createUrl('report/getSaleOfficeByProvince'), //or $this->createUrl('loadcities') if '$this' extends CController
                                    'update' => '#ReportForm_sale_office_code',
                                    'data'   => array('province_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                ),
                                'onchange' => ' $("#ReportForm_district_code").select2("val", "");
                                        $("#ReportForm_ward_code").select2("val", "");
                                        $("#ReportForm_brand_offices_id").select2("val", "");
                                        $("#ReportForm_sale_office_code").select2("val", "");
                                    ',
                                //reset value selected
                                'style'    => 'width:100%'
                            ),
                        )
                    );
                ?>
            </td>
            <td><?php echo $form->label($model, 'sale_office_code'); ?>:</td>
            <td>
                <?php
                    $this->widget(
                        'booster.widgets.TbSelect2',
                        array(
                            'model'       => $model,
                            'attribute'   => 'sale_office_code',
                            'data'        => ($model->province_code != '') ? ASaleOffices::model()->getSaleOfficesByProvince($model->province_code) : array(),
                            'htmlOptions' => array(
                                'multiple' => FALSE,
                                'prompt'   => (!ADMIN && !SUPER_ADMIN && !USER_NOT_LOCATE && Yii::app()->user->sale_offices_id != '' && !isset(Yii::app()->user->brand_offices_id)) ? NULL : Yii::t('report/menu', 'sale_offices_id'),
                                'ajax'     => array(
                                    'type'   => 'POST',
                                    'url'    => Yii::app()->createUrl('report/getBrandOfficeBySaleCode'), //or $this->createUrl('loadcities') if '$this' extends CController
                                    'update' => '#ReportForm_brand_offices_id',
                                    'data'   => array('sale_offices_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                ),
                                'onchange' => '$("#ReportForm_brand_offices_id").select2("val", "");
                                    ',
                                //reset value selected
                                'style'    => 'width:100%'
                            ),
                        )
                    );
                ?>
                <?php echo $form->error($model, 'sale_office_code'); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->label($model, 'status_type'); ?>:</td>
            <td>
                <?php
                    $accountStatus = array(1 => 'Đơn hàng', 2 => 'Thanh toán');
                    echo $form->radioButtonList($model, 'status_type', $accountStatus, array('separator' => ''));
                ?>
            </td>
            <td><?php echo $form->label($model, 'input_type'); ?>:</td>
            <td>
                <?php
                    $accountStatus = array('' => 'Tất cả', 1 => 'Tại nhà', 2 => 'Tại ĐGD');
                    echo $form->radioButtonList($model, 'input_type', $accountStatus, array('separator' => ' '));
                ?>
            </td>

        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <div class="online_status">
                    <?php echo CHtml::activeDropDownList(
                        $model,
                        'online_status',
                        $model->getOnlineStatus(),
                        array('empty' => 'Chọn tất cả', 'class' => 'dropdownlist form-control')
                    ); ?>
                </div>

                <div class="paid_status">
                    <?php echo CHtml::activeDropDownList(
                        $model,
                        'paid_status',
                        $model->getPaidStatus(),
                        array('empty' => 'Chọn tất cả', 'class' => 'dropdownlist form-control')
                    ); ?>
                </div>
            </td>

            <td>&nbsp;</td>
            <td class="brand_offices">
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
                <?php echo $form->error($model, 'offices_id'); ?>

            </td>
        </tr>

        <tr>
            <td><?php echo $form->label($model, 'payment_method'); ?>:</td>
            <td><?php
                    echo CHtml::activeDropDownList(
                        $model,
                        'payment_method',
                        $model->getAllPaymentMethod(FALSE),
                        array('empty' => 'Chọn tất cả', 'class' => 'dropdownlist form-control')
                    )
                ?>
            </td>
            <td><?php echo $form->label($model, 'item_sim_type'); ?>:</td>
            <td>
                <?php echo $form->dropDownList($model, 'item_sim_type',
                    array(
                        1 => 'Sim thường',
                        2 => 'eSIM',
                    ),
                    array(
                        'class' => 'form-control',
                        'empty' => 'Tất cả',
                    )
                ); ?>
            </td>
        </tr>

        <tr>
            <td>
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success')); ?>
            </td>
        </tr>
    </table>

    <?php $this->endWidget(); ?>
</div>
<style>
    #ReportForm_input_type label {
        margin-right: 15px;
    }

    #ReportForm_status_type label {
        margin-right: 15px;
    }

    #ReportForm_offices_id {
        font-size: 11px;
    }

    .paid_status {
        display: none;
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

        if ($('input[name="ReportForm[status_type]"]:checked').val() == 1) {
            $('.online_status').css("display", "block");
            $('.paid_status').css("display", "none");
        } else if ($('input[name="ReportForm[status_type]"]:checked').val() == 2) {
            $('.paid_status').css("display", "block");
            $('.paid_status_label').css("display", "block");
            $('.online_status').css("display", "none");
        }
        $('input:radio[name="ReportForm[status_type]"]').change(function () {
            var radio_check = $(this).val();
            if (radio_check == 2) {
                $('.paid_status').css("display", "block");
                $('.paid_status_label').css("display", "block");
                $('.online_status').css("display", "none");
            } else {
                $('.online_status').css("display", "block");
                $('.paid_status').css("display", "none");
            }

        });

    });

</script>