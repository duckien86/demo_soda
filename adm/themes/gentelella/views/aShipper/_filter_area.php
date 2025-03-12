<?php
    /* @var $this AShipperController */
    /* @var $model AShipper */
    /* @var $form CActiveForm */
?>
<div class="fillterarea form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    )); ?>
    <table>
        <tr>
            <td><?php echo $form->label($model, 'id'); ?>:</td>
            <td><?php echo $form->textField($model, 'id', array('class' => 'textbox', 'maxlength' => 255)); ?></td>
            <td><?php echo $form->label($model, 'full_name'); ?>:</td>
            <td><?php echo $form->textField($model, 'full_name', array('class' => 'textbox', 'maxlength' => 255)); ?></td>
            <td><?php echo $form->label($model, 'phone_1'); ?>:</td>
            <td><?php echo $form->textField($model, 'phone_1', array('class' => 'textbox', 'maxlength' => 255)); ?></td>
            <td><?php echo $form->label($model, 'status'); ?>:</td>
            <td><?php
                    echo CHtml::activeDropDownList(
                        $model,
                        'status',
                        array(
                            AShipper::SHIPPER_ACTIVE   => Yii::t('adm/label', 'active'),
                            AShipper::SHIPPER_INACTIVE => Yii::t('adm/label', 'inactive')
                        ),
                        array('empty' => Yii::t('adm/label', 'select_status'), 'class' => 'dropdownlist')
                    )
                ?>
            </td>
            <td rowspan='2' align='right' width='200'>
                <?php echo CHtml::submitButton(Yii::t('adm/label', 'search'), array('class' => 'btn btn-success')); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->label($model, 'province'); ?>:</td>
            <td>
                <?php
                    $this->widget(
                        'booster.widgets.TbSelect2',
                        array(
                            'model'       => $model,
                            'attribute'   => 'province',
                            'data'        => AProvince::getListProvince(),
                            'htmlOptions' => array(
                                'multiple' => FALSE,
                                'class'    => 'dropdownlist',
                                'prompt'   => Yii::t('adm/label', 'province'),
                                'ajax'     => array(
                                    'type'   => 'POST',
                                    'url'    => Yii::app()->controller->createUrl('aShipper/getDistrictByProvince'),
                                    'update' => '#AShipper_district', //or 'success' => 'function(data){...handle the data in the way you want...}',
                                    'data'   => array('provinceid' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                                ),
                            ),
                        )
                    );
                ?>
            </td>
            <td><?php echo $form->label($model, 'district'); ?>:</td>
            <td>
                <?php
                    $this->widget(
                        'booster.widgets.TbSelect2',
                        array(
                            'model'       => $model,
                            'attribute'   => 'district',
                            'data'        => ADistrict::getListDistrict(),
                            'htmlOptions' => array(
                                'multiple' => FALSE,
                                'class'    => 'dropdownlist',
                                'prompt'   => Yii::t('adm/label', 'district'),
                            ),
                        )
                    );
                ?>
            </td>
            <td><?php echo $form->label($model, 'address_detail'); ?>:</td>
            <td><?php echo $form->textField($model, 'address_detail', array('class' => 'textbox', 'maxlength' => 255)); ?></td>
        </tr>
    </table>

    <?php $this->endWidget(); ?>
</div>