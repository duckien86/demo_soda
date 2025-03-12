<?php
    /* @var $this CardController */
    /* @var $modelOrder WOrders */
    /* @var $orderDetails WOrderDetails */
    /* @var $form CActiveForm */
    /* @var $operation */
    /* @var $arr_payment */
    /* @var $amount */
?>
<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <section class="ss-bg">
        <div class="container no_pad_xs">
            <div class="checkout-process">
                <div class="col-md-4 col-md-push-8 no_pad_xs">
                    <div id="main_right_section">
                        <?php
                            Yii::app()->session['html_card_order']    = $this->renderPartial('_panel_order', array(
                                'modelOrder'   => $modelOrder,
                                'orderDetails' => $orderDetails,
                                'operation'    => $operation,
                                'amount'       => $amount,
                            ), TRUE);
                            Yii::app()->session['message_card_order'] = array(
                                'phone_contact' => $modelOrder->phone_contact,
                                'price'         => $orderDetails->price,
                                'operation'     => $operation,
                                'amount'        => $amount,
                            );
                            echo Yii::app()->session['html_card_order'];
                        ?>
                    </div>
                </div>
                <div class="col-md-8 col-md-pull-4 no_pad_xs">
                    <div id="main_left_section">
                        <div id="top_navigation">
                            <ul class="steps text-center">
                                <li class="checkout-1">
                                    <a href="#checkout1" class="disabled"><!-- data-toggle="tab"-->
                                        <?= ($operation == OrdersData::OPERATION_BUYCARD) ? '1. Mua mã thẻ' : '1. Nạp thẻ'; ?>
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
                                <div class="space_10"></div>
                                <?php $this->renderPartial('_step2', array(
                                    'modelOrder'  => $modelOrder,
                                    'operation'   => $operation,
                                    'amount'      => $amount,
                                    'arr_payment' => $arr_payment,
                                )); ?>
                            </div>
                            <!--End checkout step 2-->
                        </div>
                    </div>
                    <div class="space_10"></div>
                </div>
                <!-- end #main_right_section -->
                <div class="space_30"></div>
            </div>
        </div>
    </section>
</div>