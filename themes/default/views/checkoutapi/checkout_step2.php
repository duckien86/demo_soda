<?php
    /* @var $this CheckoutController */
    /* @var $modelOrder WOrders */
    /* @var $modelSim WSim */
    /* @var $modelPackage WPackage */
    /* @var $qr_code */
    /* @var $amount */
    /* @var $arr_payment */
    /* @var $operation */
?>
<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <section class="ss-bg">
        <div class="container no_pad_xs">
            <div class="checkout-process">
                <div class="col-md-4 col-md-push-8 no_pad_xs">
                    <div id="main_right_section">
                        <?php
                            Yii::app()->session['html_order']    = $this->renderPartial('_panel_order', array(
                                'modelSim'     => $modelSim,
                                'modelOrder'   => $modelOrder,
                                'modelPackage' => $modelPackage,
                                'amount'       => $amount,
                            ), TRUE);
                            Yii::app()->session['message_order'] = array(
                                'msisdn'        => $modelSim->msisdn,
                                'delivery_type' => $modelOrder->delivery_type,
                                'create_date'   => $modelOrder->create_date,
                                'price'         => $amount,
                                'order_id'      => $modelOrder->id,
                                'phone_contact' => $modelOrder->phone_contact,
                            );
                            echo Yii::app()->session['html_order'];
                        ?>
                    </div>
                </div>
                <div class="col-md-8 col-md-pull-4 no_pad_xs">
                    <div id="main_left_section">
                        <div id="top_navigation">
                            <ul class="steps text-center">
                                <li class="checkout-1">
                                    <a href="#checkout1" class="disabled"><!-- data-toggle="tab"-->
                                        1. Đăng ký thông tin
                                    </a>
                                </li>
                                <li class="active checkout-2">
                                    <a href="#checkout2" class="">
                                        2. Thanh toán
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <!--checkout step 1-->
                            <div class="tab-pane" id="checkout1">
                            </div>
                            <!--End checkout step 1-->
                            <!--checkout step 2-->
                            <div class="tab-pane active" id="checkout2">
                                <?php $this->renderPartial('_countdown', array()); ?>
                                <div class="space_10"></div>
                                <?php $this->renderPartial('_step2', array(
                                    'amount'      => $amount,
                                    'arr_payment' => $arr_payment,
                                    'modelOrder'  => $modelOrder,
                                    'operation'   => $operation,
                                )); ?>
                            </div>
                            <!--End checkout step 2-->
                        </div>
                    </div>
                </div>
                <!-- end #main_right_section -->
                <div class="space_30"></div>
            </div>
        </div>
    </section>
</div>