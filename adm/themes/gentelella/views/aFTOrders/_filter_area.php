<?php
    /* @var $this AFTOrdersController */
    /* @var $model AFTOrders*/
    /* @var $form CActiveForm */
?>
<div class="fillterarea form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
    )); ?>
    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-sm-8">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label($model, 'start_date'); ?>
                        <?php echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'model'          => $model,
                            'attribute'      => 'start_date',
                            'language'       => 'vi',
                            'htmlOptions'    => array(
                                'class' => 'form-control',
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
                        ), TRUE);?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label($model, 'end_date'); ?>
                        <?php echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'model'          => $model,
                            'attribute'      => 'end_date',
                            'language'       => 'vi',
                            'htmlOptions'    => array(
                                'class' => 'form-control',
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
                        ), TRUE);?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="AFTOrders_province_code">Trung tâm kinh doanh Tỉnh/TP</label>
                        <?php
                        $this->widget(
                            'booster.widgets.TbSelect2',
                            array(
                                'model'       => $model,
                                'attribute'   => 'province_code',
                                'data'        => AProvince::model()->getAllProvinceVnpTourist(),
                                'htmlOptions' => array(
                                    'class'    => 'form-control',
                                    'multiple' => FALSE,
                                    'prompt'   => '-- Chọn --',
                                ),
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label($model, 'code'); ?>
                        <?php echo $form->textField($model,'code', array(
                            'class' => 'form-control',
                        ));?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label($model,'customer');?>
                        <?php $this->widget(
                            'booster.widgets.TbSelect2',
                            array(
                                'model'       => $model,
                                'attribute'   => 'customer',
                                'data'        => AFTUsers::getAllUserName(),
                                'htmlOptions' => array(
                                    'class'    => 'form-control',
                                    'multiple' => FALSE,
                                    'prompt'   => '-- Chọn --',
                                ),
                            )
                        );?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label($model,'order_type');?>
                        <?php echo $form->dropDownList($model, 'order_type',
                            array(
                                AFTOrders::ORDER_NORMAL => "Đơn hàng thường",
                                AFTOrders::ORDER_FILE_SIM => "Đơn hàng với File sim"
                            ),
                            array(
                                'class' => 'form-control',
                                'empty' => 'Tất cả',
                            )
                        )?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label($model, 'status'); ?>
                        <?php echo $form->dropDownList($model, 'status', AFTOrders::model()->getStatusOrders(),array(
                            'class' => 'form-control',
                            'empty' => 'Tất cả',
                        ));?>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success', 'style' => 'margin-top:20px')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div>