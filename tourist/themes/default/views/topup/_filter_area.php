<?php
    /* @var $this TopupController */
    /* @var $model TTopupQueue*/
    /* @var $form CActiveForm */
?>
<div class="fillterarea form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
    )); ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-3">
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
                <div class="col-sm-3">
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
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <?php echo $form->label($model, 'status'); ?>
                        <?php echo $form->dropDownList($model,'status',TTopupQueue::getListStatus(), array(
                            'class' => 'form-control',
                            'empty' => 'Tất cả',
                        ))?>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <?php echo CHtml::submitButton(Yii::t('tourist/label', 'search'), array('class' => 'btn btn-info', 'style' => 'margin-top:22px')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $form->errorSummary($model, null, null, array('style' => 'color: red;')); ?>

    <?php $this->endWidget(); ?>
</div>