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
    <?php

        if (!ADMIN && !SUPER_ADMIN) {
            if (isset(Yii::app()->user->sale_offices_id)) {
                if (Yii::app()->user->sale_offices_id != '') {
                    $model->sale_offices_code = Yii::app()->user->sale_offices_id;
                }
            }
            if (isset(Yii::app()->user->brand_offices_id)) {
                $model->brand_offices_id = Yii::app()->user->brand_offices_id;
            }
            if (isset(Yii::app()->user->province_code)) {
                $model->province_code = Yii::app()->user->province_code;
            }
        }


    ?>
    <div class="row">
        <div class="col-md-9">
            <div class="col-md-4 col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'province_code'); ?>
                    <?php
                        $this->widget(
                            'booster.widgets.TbSelect2',
                            array(
                                'model'       => $model,
                                'attribute'   => 'province_code',
                                'data'        => AProvince::model()->getAllProvince(),
                                'htmlOptions' => array(
                                    'multiple' => FALSE,
                                    'prompt'   => (!ADMIN && !SUPER_ADMIN) ? NULL : Yii::t('report/menu', 'province_code'),
                                    'ajax'     => array(
                                        'type'   => 'POST',
                                        'url'    => Yii::app()->createUrl('aShipper/getSaleOfficeByProvince'), //or $this->createUrl('loadcities') if '$this' extends CController
                                        'update' => '#AShipper_sale_offices_code',
                                        'data'   => array('province_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                    ),
                                    'onchange' => '$("#AShipper_sale_offices_code").select2("val", "");
                                     $("#AShipper_brand_offices_id").select2("data", "");
                                     
                                    ',
                                    //reset value selected
                                    'style'    => 'width:100%'
                                ),
                            )
                        );
                    ?>
                    <?php echo $form->error($model, 'province_code'); ?>
                </div>

            </div>
            <div class="col-md-4 col-sm-4">
                <div class="form-group">

                    <?php echo $form->labelEx($model, 'sale_offices_code'); ?>
                    <?php
                        $this->widget(
                            'booster.widgets.TbSelect2',
                            array(
                                'model'       => $model,
                                'attribute'   => 'sale_offices_code',
                                'data'        => ($model->province_code != '') ? ASaleOffices::model()->getSaleOfficesByProvince($model->province_code) : array(),
                                'htmlOptions' => array(
                                    'multiple' => FALSE,
                                    'prompt'   => (!ADMIN && !SUPER_ADMIN && Yii::app()->user->sale_offices_id != '') ? NULL : Yii::t('report/menu', 'sale_offices_id'),
                                    'ajax'     => array(
                                        'type'   => 'POST',
                                        'url'    => Yii::app()->createUrl('aShipper/getBrandOfficeBySaleCode'), //or $this->createUrl('loadcities') if '$this' extends CController
                                        'update' => '#AShipper_brand_offices_id',
                                        'data'   => array('sale_offices_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                    ),
                                    'onchange' => '$("#AShipper_brand_offices_id").select2("val", "");
                                    ',
                                    //reset value selected
                                    'style'    => 'width:100%'
                                ),
                            )
                        );
                    ?>
                    <?php echo $form->error($model, 'sale_offices_code'); ?>
                </div>
            </div>
            <div class="col-md-2 col-sm-2">
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success', 'style' => 'margin-top: 20px;')); ?>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>
    <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/moment.min2.js"></script>
    <script type="text/javascript"
            src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datepicker/daterangepicker.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#AOrders_start_date').daterangepicker({
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
        $(document).ready(function () {
            $('#AOrders_end_date').daterangepicker({
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
