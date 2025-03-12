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
    <table>
        <tr>
            <td><?php echo $form->label($model, 'msisdn'); ?>:</td>
            <td>
                <?php
                    echo $form->textField($model, 'msisdn', array('class' => 'form-control', 'size' => 25, 'maxlength' => 50));
                ?>
                <?php echo $form->error($form_validate, 'msisdn'); ?>
            </td>
            <td>
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-warning')); ?>
            </td>
        </tr>
    </table>

    <?php $this->endWidget(); ?>
</div>
