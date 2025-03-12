<?php
    /* @var $this OrdersController */
    /* @var $order_info WOrders */
    /* @var $customer_info WOrders */
    /* @var $order_detail WOrderDetails */
    /* @var $order_state WOrderState */
?>
<div class="page_detail">
    <section class="ss-bg order">
        <section id="ss-box1" class="ss-box1">
            <div class="container no_pad_xs">
                <div class="ss-box1-right-all">
                    <div class="space_10"></div>
                    <div class="title_box">
                        Thông tin chi tiết đơn hàng #<?= CHtml::encode($order_info['id']); ?>
                    </div>
                    <div class="space_10"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <?php $this->renderPartial('_block_order_info', array('order_info' => $order_info)); ?>
                        </div>
                        <div class="col-md-6">
                            <?php $this->renderPartial('_block_customer_info', array('customer_info' => $customer_info)); ?>
                        </div>
                    </div>
                    <div class="space_30"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                                $this->widget(
                                    'booster.widgets.TbTabs',
                                    array(
                                        'type' => 'tabs', // 'tabs' or 'pills'
                                        'tabs' => array(
                                            array(
                                                'label'   => 'Đơn hàng chi tiết',
                                                'content' => $this->renderPartial('_block_order_detail', array('order_detail' => $order_detail), TRUE),
                                                'active'  => TRUE
                                            ),
                                            array(
                                                'label'   => 'Lịch sử đơn hàng',
                                                'content' => $this->renderPartial('_block_order_state', array('order_state' => $order_state), TRUE),
                                            ),
                                        ),
                                    )
                                ); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="fr">
                                <div class="amount">
                                    Tổng tiền đơn hàng: <span
                                        class="lbl_color_pink"><?= number_format($order_info['amount'], 0, "", ".") . 'đ'; ?></span>
                                </div>
                            </div>
                            <div class="space_10"></div>
                        </div>
                    </div>
                </div>

                <div class="space_60"></div>
            </div>
        </section>
        <!-- #ss-box1 -->
    </section>
</div>