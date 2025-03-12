<?php
    /* @var $this ASurveyReportController */
    /* @var $model ASurveyReport */
    /* @var $form CActiveForm */
?>
<div class="fillterarea form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
    )); ?>
    <?php echo $form->errorSummary($model); ?>
    <table>
        <tbody>
        <tr>
            <td><?php echo $form->label($model, 'start_date'); ?></td>
            <td>
                <?php echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model'          => $model,
                    'attribute'      => 'start_date',
                    'language'       => 'vi',
                    'htmlOptions'    => array(
                        'style' => 'width:150px; border-radius: 0',
                        'class' => 'form-control',
                        'size'  => '10',
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
            </td>
            <td><?php echo $form->label($model, 'end_date'); ?></td>
            <td>
                <?php echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model'          => $model,
                    'attribute'      => 'end_date',
                    'language'       => 'vi',
                    'htmlOptions'    => array(
                        'style' => 'width:150px; border-radius: 0',
                        'class' => 'form-control',
                        'size'  => '10',
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
            </td>
            <td rowspan="2" class="col_btn_search">
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success')); ?>
            </td>
        </tr>
        </tbody>
    </table>

    <?php $this->endWidget(); ?>
</div>