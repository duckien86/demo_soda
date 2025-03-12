<?php
    /* @var $this OrdersController */
    /* @var $order_state WOrderState */
?>
<div class="space_10"></div>
<div class="block_detail">
    <?php $this->widget('booster.widgets.TbGridView', array(
        'id'            => 'order-state-grid',
        'dataProvider'  => $order_state,
        'enableSorting' => FALSE,
        'summaryText'   => '',
        'columns'       => array(
            array(
                'name'        => Yii::t('web/portal', 'create_date'),
                'value'       => function ($data) {
                    return Chtml::encode($data['create_date']);
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
            array(
                'name'        => Yii::t('web/portal', 'confirm'),
                'type'        => 'raw',
                'value'       => function ($data) {
                    return WOrderState::getConfirmLabel($data['confirm']);
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
            array(
                'name'        => Yii::t('web/portal', 'paid'),
                'type'        => 'raw',
                'value'       => function ($data) {
                    return WOrderState::getPaidLabel($data['paid']);
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
            array(
                'name'        => Yii::t('web/portal', 'delivered'),
                'type'        => 'raw',
                'value'       => function ($data) {
                    return WOrderState::getDeliveredLabel($data['delivered']);
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
            array(
                'name'        => Yii::t('web/portal', 'note'),
                'value'       => function ($data) {
                    return Chtml::encode($data['note']);
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
        ),
    )); ?>
</div>