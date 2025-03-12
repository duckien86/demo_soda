<?php
    /* @var $this CheckoutController */
    /* @var $modelOrder AOrders */
    /* @var $modelSim ASim */
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
                        <!--checkout step 1-->
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

                        <!--End checkout step 1-->
                        <!--checkout step 2-->

                        <!--End checkout step 2-->
                    </div>

                    <div class="space_10"></div>
                </div>
                <!-- end #main_right_section -->
                <div class="space_30"></div>
            </div>
        </div>
    </section>
</div>