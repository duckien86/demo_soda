<?php
    /* @var $this AOrdersController */
    /* @var $model AOrders */
    /* @var $order_state AOrderState */
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/actions', 'view'); ?></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="row">
            <div class="col-md-6">
                <div class="title-order">
                    * Thông tin đơn hàng
                </div>
                <?php $this->widget('booster.widgets.TbDetailView', array(
                    'data'       => $model,
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
                            'name'        => "Người giao hàng",
                            'value'       => function ($data) {
                                return Chtml::encode($data->getShipperName($data['shipper_id']));
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
                            'name'        => Yii::t('web/portal', 'delivery_type'),
                            'value'       => function ($data) {
                                $brand = '';
                                if ($data['delivery_type'] == 2) {
                                    $brand = BrandOffices::model()->getBrandOfficesByOrder($data['id']);
                                }
                                if ($brand != '') {
                                    return CHtml::encode(AOrderState::getDeliveryType($data['delivery_type']) . "--" . $brand);
                                }

                                return CHtml::encode(AOrderState::getDeliveryType($data['delivery_type']));
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => Yii::t('web/portal', 'payment_method'),
                            'value'       => function ($data) {
                                return CHtml::encode(AOrders::getPaymentMethod($data['payment_method']));
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'        => 'Phòng bán hàng',
                            'value'       => function ($data) {
                                $sale = SaleOffices::model()->getSaleOfficesByOrder($data['id']);

                                return CHtml::encode($sale);
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),

                    ),
                )); ?>
            </div>
            <div class="col-md-6">
                <div class="title-order">
                    * Thông tin khách hàng
                </div>
                <?php $this->widget('booster.widgets.TbDetailView', array(
                    'data'       => $model,
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
                            'name'        => 'SĐT liên hệ',
                            'value'       => function ($data) {
                                return Chtml::encode($data['phone_contact']);
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),
                        array(
                            'name'  => 'address_detail',
                            'type'  => 'raw',
                            'value' => function ($data) {
                                return Chtml::encode($data['address_detail']);
                            },
                        ),
                        array(
                            'name'        => "Ghi chú (khách hàng)",
                            'value'       => function ($data) {
                                return Chtml::encode($data['customer_note']);
                            },
                            'htmlOptions' => array('style' => 'vertical-align:middle;'),
                        ),

                    ),
                )); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <?php if ((isset($order_detail) && !empty($order_detail)) && (isset($order_state) && !empty($order_state)) && (isset($order_shipper) && !empty($order_shipper))) :
            ?>
            <div class="space_30"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="x_content">
                        <?php $this->widget(
                            'booster.widgets.TbTabs',
                            array(
                                'type'        => 'tabs',
                                'tabs'        => array(
                                    array(
                                        'label'   => 'Chi tiết đơn hàng',
                                        'content' => $this->renderPartial('_detail', array('order_detail' => $order_detail), TRUE),
                                        'active'  => TRUE,
                                    ),
                                    array(
                                        'label'   => 'Lịch sử đơn hàng',
                                        'content' => $this->renderPartial('_view_history', array('order_state' => $order_state), TRUE),
                                    ),
                                    array(
                                        'label'   => 'Thông tin người giao hàng',
                                        'content' => $this->renderPartial('_view_shipper', array('order_shipper' => $order_shipper), TRUE),
                                    ),

                                ),
                                'htmlOptions' => array('class' => 'site_manager')
                            )
                        );
                        ?>
                    </div>
                </div>

                <div class="space_30"></div>
            </div>
        <?php endif; ?>
    </div>
</div>
<style>
    .summary {
        display: none;
    }

    .detail-view th {
        font-size: 12px;
        width: 200px;
    }
</style>
