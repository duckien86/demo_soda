<?php
/**
 * @var $this ReportController
 * @var $model TReport
 * @var $form TbActiveForm
 */
?>

<div class="filter_area">
<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'method' => 'post',
//    'enableAjaxValidation' => true,
//    'enableClientValidation' => true,
)); ?>

    <div class="row">
        <div class="col-sm-8">

            <div class="row">
                <div class="col-sm-5">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'start_date'); ?>
                        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'model'          => $model,
                            'attribute'      => 'start_date',
                            'language'       => 'vi',
                            'htmlOptions'    => array(
                                'class' => 'form-control',
                                'autocomplete' => 'off'
                            ),
                            'defaultOptions' => array(
                                'showOn'            => 'focus',
                                'dateFormat'        => 'dd/mm/yy',
                                'showOtherMonths'   => TRUE,
                                'selectOtherMonths' => TRUE,
                                'changeMonth'       => TRUE,
                                'changeYear'        => TRUE,
                                'showButtonPanel'   => TRUE,
                            )
                        ));
                        ?>
                        <?php echo $form->error($model, 'start_date'); ?>
                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'end_date'); ?>
                        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'model'          => $model,
                            'attribute'      => 'end_date',
                            'language'       => 'vi',
                            'htmlOptions'    => array(
                                'class' => 'form-control',
                                'autocomplete' => 'off'
                            ),
                            'defaultOptions' => array(
                                'showOn'            => 'focus',
                                'dateFormat'        => 'dd/mm/yy',
                                'showOtherMonths'   => TRUE,
                                'selectOtherMonths' => TRUE,
                                'changeMonth'       => TRUE,
                                'changeYear'        => TRUE,
                                'showButtonPanel'   => TRUE,
                            )
                        ));
                        ?>
                        <?php echo $form->error($model, 'end_date'); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <?php echo CHtml::submitButton('Tra cứu', array(
                            'class' => 'btn btn-primary',
                        )); ?>
                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="form-group">
                        <?php echo $form->checkBox($model, 'on_detail', array(
                            'value' => 'on',
                            'style' => 'margin-top: 10px'
                        ))?>
                        Xem chi tiết
                    </div>
                </div>
            </div>

        </div>
    </div>

<?php $this->endWidget()?>
</div>
