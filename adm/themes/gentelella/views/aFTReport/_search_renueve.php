<?php
/**
 * @var $this AFTReportController
 * @var $model AFTReport
 * @var $form CActiveForm
 */
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
                        'autocomplete'  => 'off'
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
                        'autocomplete'  => 'off'
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
                        'data'        => AProvince::model()->getAllProvinceVnpTourist(),
                        'htmlOptions' => array(
                            'multiple' => FALSE,
                            'prompt'   => 'Chọn TTKD',
                            'style'    => 'width:100%',
                        ),
                    )
                );
                ?>
            </td>

            <td><?php echo $form->label($model, 'order_type'); ?>:</td>
            <td>
                <?php echo $form->dropDownList($model,'order_type',array(
                    AFTReport::ORDER_NORMAL => 'Đơn hàng KHDN',
                    AFTReport::ORDER_CTV => 'Đơn hàng CTV',
                ),array(
                    'empty'     => 'Tất cả',
                    'class'     => 'dropdownlist form-control',
                )); ?>
            </td>
        </tr>

        <tr>
            <td><?php echo $form->label($model, 'status_order'); ?>:</td>
            <td><?php
                echo CHtml::activeDropDownList(
                    $model,
                    'status_order',
                    AFTOrders::model()->getListStatusOrderSimRevenue(),
                    array('empty' => Yii::t('report/menu', 'status_order'), 'class' => 'dropdownlist form-control')
                )
                ?>
            </td>

            <td><?php echo $form->label($model, 'package_id'); ?>:</td>
            <td>
                <?php
                $this->widget(
                    'booster.widgets.TbSelect2',
                    array(
                        'model'       => $model,
                        'attribute'   => 'package_id',
                        'data'        => CHtml::listData(AFTPackage::model()->findAll(), 'id', 'name'),
                        'htmlOptions' => array(
                            'multiple' => FALSE,
                            'prompt'   => 'Tất cả',
                            'style'    => 'width:100%',
                        ),
                    )
                );
                ?>
            </td>
        </tr>

        <tr>
            <td><?php echo $form->label($model, 'customer_id')?></td>
            <td>
                <?php
                $this->widget(
                    'booster.widgets.TbSelect2',
                    array(
                        'model'       => $model,
                        'attribute'   => 'customer_id',
                        'data'        => AFTUsers::getAllUserName(),
                        'htmlOptions' => array(
                            'multiple' => FALSE,
                            'prompt'   => 'Tất cả',
                            'style'    => 'width:100%',
                            'class'    => '',
                        ),
                    )
                );
                ?>
            </td>
        </tr>

        <tr>
            <td>
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success')); ?>
            </td>
            <td>
                <?php echo $form->checkBox($model,'on_detail', array(
                        'value' => 'on'
                    ));
                ?>
                Hiện thi chi tiết
            </td>
        </tr>
    </table>

    <?php $this->endWidget(); ?>
</div>

<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/moment.min2.js"></script>
<script type="text/javascript"
        src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datepicker/daterangepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $('#AFTReport_start_date').daterangepicker({
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

        $('#AFTReport_end_date').daterangepicker({
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