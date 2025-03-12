<?php
    /* @var $this APackageController */
    /* @var $model APackage */
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
            <td width="80"><?php echo $form->label($model, 'name'); ?></td>
            <td><?php echo $form->textField($model, 'name', array('class' => 'textbox', 'maxlength' => 255)); ?></td>
            <td><?php echo $form->label($model, 'type'); ?></td>
            <td>
                <?php
                    echo CHtml::activeDropDownList(
                        $model,
                        'type',
                        $model->getAllPackageType(),
                        array('empty' => Yii::t('adm/label', 'select'), 'class' => 'dropdownlist')
                    )
                ?>
            </td>
            <td class="col_btn_search">

            </td>
        </tr>
        <tr>
            <td><?php echo $form->label($model, 'code'); ?></td>
            <td><?php echo $form->textField($model, 'code', array('class' => 'textbox', 'maxlength' => 20)); ?></td>
            <td><?php echo $form->label($model, 'status'); ?></td>
            <td>
                <?php
                    echo CHtml::activeDropDownList(
                        $model,
                        'status',
                        array(
                            APackage::PACKAGE_ACTIVE   => Yii::t('adm/label', 'active'),
                            APackage::PACKAGE_INACTIVE => Yii::t('adm/label', 'inactive')
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