<?php
    /* @var $this APrepaidtopostpaidController */
    /* @var $model APrepaidToPostpaid*/
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
                <div class="col-sm-5">
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
                <div class="col-sm-5">
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
                <div class="col-sm-5">
                    <div class="form-group">
                        <?php echo $form->label($model,'province_code')?>
                        <?php $this->widget('booster.widgets.TbSelect2', array(
                            'model'       => $model,
                            'attribute'   => 'province_code',
                            'data'        => AProvince::model()->getAllProvince(),
                            'htmlOptions' => array(
                                'class'    => 'form-control',
                                'style'    => 'font-size:13px',
                                'multiple' => FALSE,
                                'prompt'   => Yii::t('adm/label', 'select'),
                            ),
                        ));?>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <?php echo $form->label($model,'status')?>
                        <?php echo $form->dropDownList($model,'status', APrepaidToPostpaid::getListStatusLabelReport(),array(
                            'class' => 'form-control',
                            'empty' => Yii::t('adm/label', 'all'),
                        ))?>
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