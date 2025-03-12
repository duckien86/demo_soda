<?php
/**
 * @var $this OrderCtvController
 * @var $model TOrders
 */

$this->step = OrderCtvController::STEP_COMPLETE_ORDER
?>

<div id="order">

    <?php $this->renderPartial('/orderCtv/_block_form_wizard');?>

    <h3 style="margin-top:0; font-size: 16px">
        <?php echo Yii::t('tourist/message','order_confirm_success', array(
            '{code}' => CHtml::link(CHtml::encode($model->code), Yii::app()->createUrl('orderCtv/view', array('id' => $model->id))),
        ));?>
    </h3>
    <div>
        <?php echo CHtml::link(Yii::t('tourist/label','back_home'), Yii::app()->createUrl('site/index'), array(
            'id' => 'btn-back_home',
            'class' => 'btn',
        ))?>
        <?php echo CHtml::link(Yii::t('tourist/label','orders'), Yii::app()->createUrl('orderCtv/admin'), array(
            'id' => 'btn-order_admin',
            'class' => 'btn',
        ))?>
    </div>
</div>
