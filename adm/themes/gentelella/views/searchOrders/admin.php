<?php
    /* @var $this CskhOrdersController */
    /* @var $model CskhOrders */

    $this->breadcrumbs = array(
        'Tra cứu đơn hàng' => array('admin'),
        'Tra cứu',
    );
?>

<div class="x_panel">

    <div class="x_title">
        <h3>Tra cứu đơn hàng</h3>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $this->renderPartial('_search', array('model' => $model)); ?>
        </div>
    </div>
    <div class="row border-row">
        <?php if (isset($data_output_detail) && !empty($data_output_detail)): ?>
            <div class="col-md-12">
                <?php if (isset($data_output_detail['order_info']) && !empty($data_output_detail['order_info'])): ?>
                    <div class="col-md-6">
                        <div class="x_content">
                            <div class="title-order">
                                * Thông tin đơn hàng
                            </div>
                            <div class="row">
                                <?php $this->widget('booster.widgets.TbDetailView', array(
                                    'data'       => $data_output_detail['order_info'],
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
                                                return Chtml::encode(AOrders::getStatus($data['id']));
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


                                    ),
                                )); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($data_output_detail['customer_info']) && !empty($data_output_detail['order_info'])): ?>
                    <div class="col-md-6">
                        <div class="x_content">
                            <div class="title-order">
                                * Thông tin khách hàng
                            </div>
                            <div class="row">
                                <?php $this->widget('booster.widgets.TbDetailView', array(
                                    'data'       => $data_output_detail['customer_info'],
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
                                            'name'  => 'Địa chỉ liên hệ',
                                            'type'  => 'raw',
                                            'value' => $data_output_detail['customer_info']['address_detail'] . " - " . $data_output_detail['customer_info']['district'] . " - " . $data_output_detail['customer_info']['province'],
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
                <?php endif; ?>
            </div>
        <?php else:
            if ($post == 1) {
                ?>
                <div class="not-found-data">
                    <?php echo "Không có dữ liệu !"; ?>
                </div>
            <?php } endif; ?>
    </div>

    <?php if ((isset($order_detail) && !empty($order_detail)) || (isset($data_history) && !empty($order_detai))) :
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
                                    'content' => $this->renderPartial('_history_order', array('data_history' => $data_history), TRUE),
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
<style>
    .summary {
        display: none;
    }

    .detail-view th {
        font-size: 12px;
        width: 200px;
    }
</style>

