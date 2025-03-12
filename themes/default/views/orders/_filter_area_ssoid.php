<?php
    /* @var $this OrdersController */
    /* @var $modelSearch SearchOrderForm */
    /* @var $form CActiveForm */
?>
<div class="fillterarea form">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                   => 'filter_order_ssoid',
        'method'               => 'POST',
        'action'               => Yii::app()->controller->createUrl('orders/searchAjax'),
        'enableAjaxValidation' => TRUE,
    )); ?>
    <div class="form-group">
        <div class="item">
            <?php echo $form->label($modelSearch, 'from_date'); ?>
            <?php
                $this->widget(
                    'booster.widgets.TbDatePicker',
                    array(
                        'model'       => $modelSearch,
                        'attribute'   => 'from_date',
                        'options'     => array(
                            'language' => 'vi'
                        ),
                        'htmlOptions' => array('class' => 'datetime', 'placeholder' => '')
                    )
                );
            ?>
        </div>
        <div class="item">
            <?php echo $form->label($modelSearch, 'to_date'); ?>
            <?php
                $this->widget(
                    'booster.widgets.TbDatePicker',
                    array(
                        'model'       => $modelSearch,
                        'attribute'   => 'to_date',
                        'options'     => array(
                            'language' => 'vi'
                        ),
                        'htmlOptions' => array('class' => 'datetime', 'placeholder' => '')
                    )
                );
            ?>
        </div>
        <div class="item">
            <?php echo CHtml::submitButton(Yii::t('web/portal', 'search'),
                array('class' => 'btn btn_green')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div>
<div class="space_10"></div>