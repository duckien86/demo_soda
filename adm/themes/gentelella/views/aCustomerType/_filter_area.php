<?php
    /* @var $this ACustomerTypeController */
    /* @var $model ACustomerType */
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
            <td width="100"><?php echo $form->label($model, 'name'); ?>:</td>
            <td colspan="5"><?php echo $form->textField($model, 'name', array('class' => 'textbox', 'maxlength' => 255)); ?></td>
            <td><?php echo $form->label($model, 'status'); ?>:</td>
            <td>
                <?php
                    echo CHtml::activeDropDownList(
                        $model,
                        'status',
                        array(
                            ACustomerType::CUSTOMER_TYPE_ACTIVE   => Yii::t('adm/label', 'active'),
                            ACustomerType::CUSTOMER_TYPE_INACTIVE => Yii::t('adm/label', 'inactive')
                        ),
                        array('empty' => Yii::t('adm/label', 'select_status'), 'class' => 'dropdownlist')
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