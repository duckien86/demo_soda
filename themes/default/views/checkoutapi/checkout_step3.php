<?php
    /* @var $this CheckoutController */
    /* @var $modelOrder WOrders */
    /* @var $modelSim WSim */
    /* @var $payment_method WPaymentMethod */
?>
<div class="container page_detail">
    <div class="checkout-process">
        <div id="top_navigation">
            <ul class="steps text-center">
                <li class="checkout-1">
                    <a href="#checkout1" class="disabled"><!-- data-toggle="tab"-->
                        <span class="bullet-checkout">  <span class="number">1</span> </span>
                        Đăng ký thông tin sim
                    </a>
                </li>
                <li class="checkout-2">
                    <a href="#checkout2" class="disabled">
                        <span class="bullet-checkout">  <span class="number">2</span> </span>
                        Thông tin giao hàng
                    </a>
                </li>
                <li class="active checkout-3">
                    <a href="#checkout3" class="">
                        <span class="bullet-checkout"><span class="number">3</span> </span>
                        Phương thức thanh toán
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-md-8 no_pad">
            <div id="main_left_section">
                <div class="tab-content">
                    <!--checkout step 1-->
                    <div class="tab-pane" id="checkout1">
                    </div>
                    <!--End checkout step 1-->
                    <!--checkout step 2-->
                    <div class="tab-pane" id="checkout2">
                    </div>
                    <!--End checkout step 2-->
                    <!--checkout step 3-->
                    <div class="tab-pane active" id="checkout3">
                        <?php $this->renderPartial('_step3', array(
                            'payment_method' => $payment_method,
                        )); ?>
                    </div>
                    <!--End checkout step 3-->
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div id="main_right_section">
                <?php $this->renderPartial('_panel_order', array('modelSim' => $modelSim)); ?>
            </div>
        </div>
        <!-- end #main_right_section -->
    </div>
</div>
<div class="space_30"></div>