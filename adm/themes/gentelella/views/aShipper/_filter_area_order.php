<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */
    /* @var $form CActiveForm */
    /* @var $shipper_id */
?>
<div class="fillterarea form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route, array('id' => $shipper_id)),
        'method' => 'get',
    )); ?>
    <table>
        <tr>
            <td><?php echo $form->label($model, 'id'); ?>:</td>
            <td><?php echo $form->textField($model, 'id', array('class' => 'textbox', 'maxlength' => 255)); ?></td>
            <td><?php echo $form->label($model, 'status'); ?>:</td>
            <td><?php
                    echo CHtml::activeDropDownList(
                        $model,
                        'status',
                        $model->getAllStatus(),
                        array('empty' => Yii::t('adm/label', 'select_status'), 'class' => 'dropdownlist')
                    )
                ?>
            </td>
            <td><?php echo $form->label($model, 'product_id'); ?>:</td>
            <td><?php echo $form->textField($model, 'product_id', array('class' => 'textbox', 'maxlength' => 255)); ?></td>
            <td><?php echo $form->label($model, 'create_date'); ?>:</td>
            <td>
                <?php
                    $this->widget(
                        'booster.widgets.TbDatePicker',
                        array(
                            'model'       => $model,
                            'attribute'   => 'create_date',
                            'options'     => array(
                                'language' => 'vi'
                            ),
                            'htmlOptions' => array('class' => 'datetime', 'placeholder' => '')
                        )
                    );
                ?>
            </td>
            <td rowspan='2' align='right' width='200'>
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success')); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->label($model, 'full_name'); ?>:</td>
            <td><?php echo $form->textField($model, 'full_name', array('class' => 'textbox', 'maxlength' => 255)); ?></td>
            <td><?php echo $form->label($model, 'personal_id'); ?>:</td>
            <td><?php echo $form->textField($model, 'personal_id', array('class' => 'textbox', 'maxlength' => 255)); ?></td>
            <td><?php echo $form->label($model, 'phone_contact'); ?>:</td>
            <td><?php echo $form->textField($model, 'phone_contact', array('class' => 'textbox', 'maxlength' => 255)); ?></td>
            <td><?php echo $form->label($model, 'last_update'); ?>:</td>
            <td>
                <?php
                    $this->widget(
                        'booster.widgets.TbDatePicker',
                        array(
                            'model'       => $model,
                            'attribute'   => 'last_update',
                            'options'     => array(
                                'language' => 'vi'
                            ),
                            'htmlOptions' => array('class' => 'datetime', 'placeholder' => '')
                        )
                    );
                ?>
            </td>
        </tr>
    </table>

    <?php $this->endWidget(); ?>
</div>