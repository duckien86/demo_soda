<?php
    /* @var $this ANewsController */
    /* @var $model ANews */
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
            <td width="100"><?php echo $form->label($model, 'title'); ?></td>
            <td><?php echo $form->textField($model, 'title', array('class' => 'textbox', 'maxlength' => 255)); ?></td>
            <td><?php echo $form->label($model, 'status'); ?></td>
            <td>
                <?php
                    echo CHtml::activeDropDownList(
                        $model,
                        'status',
                        array(
                            ANews::NEWS_ACTIVE   => Yii::t('adm/label', 'active'),
                            ANews::NEWS_INACTIVE => Yii::t('adm/label', 'inactive')
                        ),
                        array('empty' => Yii::t('adm/label', 'select_status'), 'class' => 'dropdownlist')
                    )
                ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->label($model, 'create_date'); ?></td>
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

            <td><?php echo $form->label($model, 'categories_id'); ?></td>
            <td>
                <?php echo $form->dropDownList($model, 'categories_id', ANewsCategories::getAllCategories(),
                    array('prompt' => Yii::t('adm/label', 'select_category'), 'class' => 'dropdownlist')); ?>
            </td>
            <td rowspan="2" class="col_btn_search">
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success')); ?>
            </td>
        </tr>
        </tbody>
    </table>

    <?php $this->endWidget(); ?>
</div>