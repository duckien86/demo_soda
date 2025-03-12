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
                $model->sale_office_code = Yii::app()->user->sale_offices_id;
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
        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'start_date'); ?>
                    <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </span>
                        <?php
                            echo $form->textField($model, 'start_date', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                        ?>
                    </div>
                    <?php echo $form->error($model_validate, 'start_date'); ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'end_date'); ?>
                    <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        </span>
                        <?php
                            //                    $model->end_date = ($model->isNewRecord) ? date('d/m/Y') : date('d/m/Y', strtotime($model->end_date));
                            echo $form->textField($model, 'end_date', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                        ?>
                    </div>
                    <?php echo $form->error($model_validate, 'end_date'); ?>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-3">
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
                                        'url'    => Yii::app()->createUrl('aTraffic/getSaleOfficeByProvince'), //or $this->createUrl('loadcities') if '$this' extends CController
                                        'update' => '#AOrders_sale_office_code',
                                        'data'   => array('province_code' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                    ),
                                    'onchange' => '$("#AOrders_sale_office_code").select2("val", "");
                                               
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

            <div class="col-md-3">
                <div class="form-group">

                    <?php echo $form->labelEx($model, 'sale_office_code'); ?>
                    <?php
                        $this->widget(
                            'booster.widgets.TbSelect2',
                            array(
                                'model'       => $model,
                                'attribute'   => 'sale_office_code',
                                'data'        => ($model->province_code != '') ? ASaleOffices::model()->getSaleOfficesByProvince($model->province_code) : array(),
                                'htmlOptions' => array(
                                    'multiple' => FALSE,
                                    'prompt'   => (!ADMIN && !SUPER_ADMIN && Yii::app()->user->sale_offices_id != '') ? NULL : Yii::t('report/menu', 'sale_offices_id'),
                                    //reset value selected
                                    'style'    => 'width:100%'
                                ),
                            )
                        );
                    ?>
                    <?php echo $form->error($model, 'sale_office_code'); ?>
                </div>
            </div>

        </div>
        <div class="col-md-12">
            <div class="col-md-3" style="margin-top: 25px;">
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success')); ?>
            </div>
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

        $('#AOrders_end_date').daterangepicker({
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