<?php
/**
 * @var $this ACardStoreBusinessController
 * @var $model ACardStoreBusiness
 * @var $form CActiveForm
 * @var $type string   import (nhâp kho) | export (xuất kho) | remain (tồn kho) | synthetic (tổng hợp XNT) | revenue (doanh thu) | card (sản lượng mã thẻ)
 */
?>
<div class="fillterarea form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
    )); ?>
    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-sm-8">

            <?php if(!isset($type) || (isset($type) && $type != 'remain')){ ?>
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

                <?php if(isset($type) && $type == 'import'){ ?>
            </div><div class='row'>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label($model, 'import_code');?>
                        <?php echo $form->textField($model,'import_code', array(
                            'class' => 'form-control',
                        )); ?>
                    </div>
                </div>
                <?php }?>

                <?php if(isset($type) && ($type == 'export' || $type == 'card')){ ?>
            </div><div class='row'>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label($model, 'order_code');?>
                        <?php echo $form->textField($model,'order_code', array(
                            'class' => 'form-control',
                        )); ?>
                    </div>
                </div>

                    <?php if($type == 'export'){?>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <?php echo $form->label($model, 'status');?>
                            <?php echo $form->dropDownList($model,'status', ACardStoreBusiness::getListStatusExport(), array(
                                'class' => 'form-control',
                                'empty' => 'Tất cả',
                            )); ?>
                        </div>
                    </div>
                    <?php }?>

                <?php }?>


                <div class="col-sm-2">
                    <div class="form-group">
                        <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success', 'style' => 'margin-top:20px')); ?>
                    </div>
                </div>
            </div>
            <?php } ?>


            <?php if(isset($type) && $type == 'remain'){?>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="ACardStoreBusiness_create_date">Ngày tra cứu</label>
                            <?php echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                'model'          => $model,
                                'attribute'      => 'create_date',
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
                    <div class="col-sm-2">
                        <div class="form-group">
                            <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success', 'style' => 'margin-top:20px')); ?>
                        </div>
                    </div>
                </div>

            <?php } ?>



        </div>
    </div>

    <?php $this->endWidget(); ?>
</div>