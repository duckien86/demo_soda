<?php
    /* @var $this OrdersController */
    /* @var $customer_info WOrders */
?>
<div class="block_detail">
    <div class="title uppercase">
        Thông tin người nhận hàng
    </div>
    <div class="line"></div>
    <?php $this->widget('booster.widgets.TbDetailView', array(
        'data'       => $customer_info,
        'type'       => '',
        'attributes' => array(
            array(
                'name'        => Yii::t('web/portal', 'full_name'),
                'value'       => function ($data) {
                    return Chtml::encode($data['full_name']);
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
            array(
                'name'        => Yii::t('web/portal', 'phone_contact'),
                'value'       => function ($data) {
                    return Chtml::encode($data['phone_contact']);
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
            array(
                'name'  => Yii::t('web/portal', 'address_detail'),
                'type'  => 'raw',
                'value' => $customer_info['address_detail'] . " - " . $customer_info['district_code'] . " - " . $customer_info['province_code'],
            ),
            array(
                'name'        => Yii::t('web/portal', 'customer_note'),
                'value'       => function ($data) {
                    return Chtml::encode($data['customer_note']);
                },
                'htmlOptions' => array('style' => 'vertical-align:middle;'),
            ),
        ),
    )); ?>
</div>