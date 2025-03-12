<?php
    /* @var $this ASubscriptionTypeController */
    /* @var $model ASubscriptionType */
    /* @var $form CActiveForm */
?>
<div class="fillterarea form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    )); ?>
    <table>
        <tbody>
        <tr>
            <td width="80"><?php echo $form->label($model, 'name'); ?>:</td>
            <td><?php echo $form->textField($model, 'name', array('class' => 'textbox', 'maxlength' => 255)); ?></td>
            <td><?php echo $form->label($model, 'type'); ?>:</td>
            <td>
                <?php
                    echo CHtml::activeDropDownList(
                        $model,
                        'type',
                        $model->getAllSubscriptionType(),
                        array('empty' => Yii::t('adm/label', 'select'), 'class' => 'dropdownlist')
                    )
                ?>
            </td>
            <td rowspan="2" class="col_btn_search">
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success')); ?>
            </td>
        </tr>
        </tbody>
    </table>

    <?php $this->endWidget(); ?>
</div>