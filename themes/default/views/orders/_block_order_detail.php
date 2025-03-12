<?php
    /* @var $this OrdersController */
    /* @var $order_detail WOrderDetails */
?>
<div class="space_10"></div>
<div class="block_detail">
    <?php $this->widget('booster.widgets.TbGridView', array(
        'id'            => 'order-detail-grid',
        'dataProvider'  => $order_detail,
        'enableSorting' => FALSE,
        'summaryText'   => '',
        'columns'       => array(
            array(
                'name'        => Yii::t('web/portal', 'item_name'),
                'value'       => function ($data) {
                    return ($data['type'] == WOrderDetails::TYPE_SIM || $data['type'] == WOrderDetails::TYPE_PACKAGE) ? CHtml::encode($data['item_name']) : '';
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
            array(
                'name'        => Yii::t('web/portal', 'type_detail'),
                'value'       => function ($data) {
                    return WOrderDetails::getTypeLabel($data['type']);
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
            array(
                'name'        => Yii::t('web/portal', 'quantity'),
                'value'       => function ($data) {
                    return Chtml::encode($data['quantity']);
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
            array(
                'name'        => Yii::t('web/portal', 'price'),
                'value'       => function ($data) {
                    return number_format($data['price'], 0, "", ".") . 'đ';
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
            array(
                'name'        => Yii::t('web/portal', 'total_price'),
                'value'       => function ($data) {
                    return number_format($data['total_price'], 0, "", ".") . 'đ';
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
        ),
    )); ?>
</div>