<?php
    /* @var $this OrdersController */
    /* @var $order_info WOrders */
?>
<div class="block_detail">
    <div class="title uppercase">
        Thông tin đơn hàng
    </div>
    <div class="line"></div>
    <?php $this->widget('booster.widgets.TbDetailView', array(
        'data'       => $order_info,
        'type'       => '',
        'attributes' => array(
            array(
                'name'        => Yii::t('web/portal', 'order_id'),
                'value'       => function ($data) {
                    return Chtml::encode($data['id']);
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
            array(
                'name'        => Yii::t('web/portal', 'create_date'),
                'value'       => function ($data) {
                    return Chtml::encode($data['create_date']);
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
            array(
                'name'        => Yii::t('web/portal', 'status'),
                'value'       => function ($data) {
                    return WOrders::getStatusLabel($data['delivered']);
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
            array(
                'name'        => Yii::t('web/portal', 'payment_method'),
                'value'       => function ($data) {
                    return WPaymentMethod::getPaymentMethodLabel($data['payment_method']);
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
        ),
    )); ?>
</div>