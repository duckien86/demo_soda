<?php
/**
 * @var $this AWCMatchController
 * @var $model AWCMatch
 * @var $form CActiveForm
 */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'                   => 'awcmatch-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => FALSE,
        'htmlOptions'          => array('enctype' => 'multipart/form-data')
    )); ?>

    <div class="form-group">
        <?= Yii::t('adm/actions', 'required_field') ?>
    </div>
    <?php echo $form->errorSummary($model); ?>

    <div class="form-group">
        <div class="row">
            <?php echo $form->labelEx($model, 'team_code_1', array('class' => 'col-md-12')); ?>
            <div class="col-md-5">
                <?php $this->widget(
                    'booster.widgets.TbSelect2',
                    array(
                        'model'       => $model,
                        'attribute'   => 'team_code_1',
                        'data'        => CHtml::listData(AWCTeam::getAllTeam(),'code','name'),
                        'htmlOptions' => array(
                            'class'    => 'form-control',
                            'multiple' => FALSE,
                            'prompt'   => 'Chọn đội 1',
                        ),
                    )
                ); ?>
            </div>
            <?php echo $form->error($model, 'team_code_1'); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <?php echo $form->labelEx($model, 'team_code_2', array('class' => 'col-md-12')); ?>
            <div class="col-md-5">
                <?php $this->widget(
                    'booster.widgets.TbSelect2',
                    array(
                        'model'       => $model,
                        'attribute'   => 'team_code_2',
                        'data'        => CHtml::listData(AWCTeam::getAllTeam(),'code','name'),
                        'htmlOptions' => array(
                            'class'    => 'form-control',
                            'multiple' => FALSE,
                            'prompt'   => 'Chọn đội 2',
                        ),
                    )
                ); ?>
            </div>
            <?php echo $form->error($model, 'team_code_2'); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <?php echo $form->labelEx($model, 'type', array('class' => 'col-md-12')); ?>
            <div class="col-md-5">
                <?php echo $form->dropDownList($model,'type', AWCMatch::getListType(),array('class' => 'form-control'))?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <?php echo $form->labelEx($model, 'start_time', array('class' => 'col-md-12')); ?>
            <div class="col-md-2">
                Ngày
                <?php echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model'          => $model,
                    'attribute'      => 'start_time',
                    'language'       => 'vi',
                    'htmlOptions'    => array(
                        'style' => 'border-radius: 0',
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
            <div class="col-md-1">
                Giờ
                <?php echo $form->dropDownList($model, 'hour', Utils::getHours(TRUE), array(
                    'class' => 'form-control'
                ))?>
            </div>
            <div class="col-md-1">
                Phút
                <?php echo $form->dropDownList($model, 'minute', Utils::getMinutes(), array(
                    'class' => 'form-control'
                ))?>
            </div>
            <?php echo $form->error($model, 'start_time'); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <?php echo $form->labelEx($model, 'status', array('class' => 'col-md-12')); ?>
            <div class="col-md-2">
                <?php echo $form->dropDownList($model,'status', array(
                    AWCMatch::ACTIVE => Yii::t('adm/label','active'),
                    AWCMatch::INACTIVE => Yii::t('adm/label','inactive'),
                ),array('class' => 'form-control'))?>
            </div>
        </div>
    </div>

    <div class="form-group buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('adm/actions', 'create') : Yii::t('adm/actions', 'save'), array('class' => 'btn btn-success')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<script>

</script>