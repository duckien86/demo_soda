<?php
    /* @var $this ACardStoreBusinessController */
    /* @var $model ACardStoreBusiness*/
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
                        <?php echo $form->label($model,'type_search_date')?>
                        <?php echo $form->dropDownList($model,'type_search_date', ACardStoreBusiness::getListTypeSearchDate(), array(
                            'class' => 'form-control',
                        ))?>
                    </div>
                </div>
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
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label($model, 'serial'); ?>
                        <?php echo $form->textField($model, 'serial', array(
                            'class' => 'form-control',
                        ));?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label($model, 'pin'); ?>
                        <?php echo $form->textField($model, 'pin', array(
                            'class' => 'form-control',
                        ));?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label($model, 'import_code'); ?>
                        <?php echo $form->textField($model, 'import_code', array(
                            'class' => 'form-control',
                        ));?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label($model, 'value'); ?>
                        <?php echo $form->numberField($model,'value', array(
                            'class' => 'form-control',
                        ))?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label($model, 'status'); ?>
                        <?php echo $form->dropDownList($model,'status',ACardStoreBusiness::getListStatus(), array(
                            'class' => 'form-control',
                            'empty' => 'Tất cả',
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