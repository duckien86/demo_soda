<?php
    /* @var $this CheckoutController */
    /* @var $modelOrder WOrders */
    /* @var $modelSim WSim */
    /* @var $modelPackage WPackage */
    /* @var $package WPackage */
    /* @var $province WProvince */
    /* @var $district WDistrict */
    /* @var $ward WWard */
    /* @var $brand_offices WBrandOffices */
    /* @var $amount */
    /* @var $change_sim_type */
?>
<div class="page_detail">
    <section class="ss-bg">
        <div class="container no_pad_xs">
            <div class="checkout-process">
                <div class="col-md-4 col-md-push-8 no_pad_xs">
                    <div id="main_right_section">
                        <?php $this->renderPartial('_panel_order', array(
                            'modelSim'     => $modelSim,
                            'modelOrder'   => $modelOrder,
                            'modelPackage' => $modelPackage,
                            'amount'       => $amount,
                        )); ?>
                    </div>
                </div>
                <div class="col-md-8 col-md-pull-4 no_pad_xs">
                    <div id="main_left_section">
                        <div id="top_navigation">
                            <ul class="steps text-center">
                                <li class="active checkout-1">
                                    <a href="#checkout1"><!-- data-toggle="tab"-->
                                        1. Đăng ký thông tin
                                    </a>
                                </li>
                                <li class="checkout-2">
                                    <a href="#checkout2" class="disabled">
                                        2. Thanh toán
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <!--checkout step 1-->
                            <div class="tab-pane active" id="checkout1">
                                <?php $this->renderPartial('_countdown', array()); ?>
                                <?php $this->renderPartial('_step1',
                                    array('modelSim'        => $modelSim,
                                          'modelOrder'      => $modelOrder,
                                          'modelPackage'    => $modelPackage,
                                          'package'         => $package,
                                          'province'        => $province,
                                          'district'        => $district,
                                          'ward'            => $ward,
                                          'brand_offices'   => $brand_offices,
                                          'change_sim_type' => $change_sim_type,
                                    )); ?>
                            </div>
                            <!--End checkout step 1-->
                            <!--checkout step 2-->
                            <div class="tab-pane" id="checkout2">
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