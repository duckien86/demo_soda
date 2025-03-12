<?php
    /* @var $this OrdersController */
    /* @var $modelSearch SearchOrderForm */
    /* @var $orders */
    /* @var $packages  */
?>
<div class="page_detail">
    <section class="ss-bg order">
        <section id="ss-box1" class="ss-box1">
            <div class="container no_pad_xs">
                <div class="ss-box1-right-all">
                    <?php
                        $this->widget(
                            'booster.widgets.TbTabs',
                            array(
                                'type' => 'tabs', // 'tabs' or 'pills'
                                'tabs' => array(
                                    array(
                                        'label'   => 'Dịch vụ của tôi',
                                        'content' => $this->renderPartial('_list_package',array('packages'=>$packages), TRUE),
                                        'active'  => TRUE
                                    ),
                                    array(
                                        'label'   => 'Lịch sử giao dịch',
                                        'content' => $this->renderPartial('_orders_by_sso_id', array('orders' => $orders, 'modelSearch' => $modelSearch), TRUE),
                                    ),
                                ),
                            )
                        ); ?>
                </div>

                <div class="space_10"></div>
            </div>
        </section>
        <!-- #ss-box1 -->
    </section>
</div>