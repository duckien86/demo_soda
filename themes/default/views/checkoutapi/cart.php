<?php
    /* @var $this CheckoutController */
    /* @var $orders WOrders */
?>
<div class="container-fluid">
    <div class="space_10"></div>
    <?php $this->widget('booster.widgets.TbGridView', array(
        'id'           => 'orders-grid',
        'dataProvider' => $orders,
        'summaryText'  => '',
        'columns'      => array(
            array(
                'name'        => 'id',
                'htmlOptions' => array('style' => 'width:100px;vertical-align:middle;'),
            ),
            array(
                'name'        => 'promo_code',
                'htmlOptions' => array('style' => 'width:80px;text-align: center;word-break: break-word;vertical-align:middle;'),
            ),
            array(
                'name'        => 'full_name',
                'htmlOptions' => array('style' => 'width:100px;text-align: center;word-break: break-word;vertical-align:middle;'),
            ),
            array(
                'name'        => 'personal_id',
                'htmlOptions' => array('style' => 'width:90px;text-align: center;word-break: break-word;vertical-align:middle;'),
            ),
            array(
                'name'        => 'birthday',
                'value'       => 'date("d/m/Y",strtotime($data->birthday))',
                'htmlOptions' => array('style' => 'width:90px;text-align: center;word-break: break-word;vertical-align:middle;'),
            ),
            array(
                'name'        => 'phone_contact',
                'htmlOptions' => array('style' => 'width:90px;text-align: center;word-break: break-word;vertical-align:middle;'),
            ),
            array(
                'name'        => 'address_detail',
                'value'       => '$data->address_detail." - ".$data->getDistrict($data->district)." - ".$data->getProvince($data->province)',
                'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
            ),
            array(
                'name'        => 'create_date',
                'htmlOptions' => array('style' => 'width:140px;text-align: center;word-break: break-word;vertical-align:middle;'),
            ),
            array(
                'name'        => 'last_update',
                'htmlOptions' => array('style' => 'width:140px;text-align: center;word-break: break-word;vertical-align:middle;'),
            ),
            array(
                'name'        => 'shipper_id',
                'type'        => 'raw',
                'value'       => function ($data) {
                    return $data->getShipperName($data->shipper_id);
                },
                'htmlOptions' => array('style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
            ),
            array(
                'name'        => 'status',
                'type'        => 'raw',
                'value'       => function ($data) {
                    return $data->getStatusLabel($data->detail->status);
                },
                'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center;word-break: break-word;vertical-align:middle;'),
            )
        ),
    )); ?>
</div>