<?php
/**
 * @var $this AFTReportController
 * @var $model AFTReport
 * @var $form CActiveForm
 */
?>
<div class="fillterarea form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
    )); ?>

    <?php echo $form->errorSummary($model);?>

    <table>
        <tr>
            <td><?php echo $form->label($model, 'msisdn'); ?></td>
            <td>
                <?php echo $form->textField($model,'msisdn',array(
                    'class' => 'form-control'
                )); ?>
            </td>
            <td>
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success')); ?>
            </td>
        </tr>
    </table>

    <?php $this->endWidget(); ?>
</div>
