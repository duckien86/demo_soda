<?php
    /* @var $this PackageController */
    /* @var $modelOrder WOrders */
    /* @var $modelPackage WPackage */
    /* @var $otpModel OtpForm */
    /* @var $otpForm CActiveForm */
    /* @var $package_flexible */
?>

<div class="page_detail">
    <?php $this->renderPartial('/layouts/_block_service'); ?>
    <section class="ss-bg">
        <div class="container">
            <div class="checkout-process">
                <div class="col-md-4 col-md-push-8 no_pad_xs">
                    <div id="main_right_section">
                        <?php
                            if ($modelPackage->type == WPackage::PACKAGE_FLEXIBLE) {
                                $html_content = $this->renderPartial('_panel_order_flexible', array(
                                    'modelOrder'       => $modelOrder,
                                    'modelPackage'     => $modelPackage,
                                    'package_flexible' => $package_flexible,
                                ), TRUE);
                            } else {
                                $html_content = $this->renderPartial('_panel_order', array(
                                    'modelOrder'   => $modelOrder,
                                    'modelPackage' => $modelPackage,
                                ), TRUE);
                            }

                            $cache_key = 'html_pack_order_' . $modelOrder->id;
                            $arr_cache = array(
                                'html_content' => $html_content,
                                'modelPackage' => $modelPackage,
                            );

                            $pack_cache_key         = new CHttpCookie('html_package_cache_key', $cache_key);
                            $pack_cache_key->expire = time() + 60 * 30;//30'

                            Yii::app()->request->cookies['html_package_cache_key'] = $pack_cache_key;
                            //set cache order
                            Yii::app()->cache->set($cache_key, $arr_cache);

                            echo $html_content;
                        ?>
                    </div>
                </div>
                <div class="col-md-8 col-md-pull-4">
                    <div id="main_left_section">
                        <div class="form">
                            <?php $otpForm = $this->beginWidget('CActiveForm', array(
                                'id'                   => 'confirm_form',
                                'enableAjaxValidation' => TRUE,
                            ));
                                if ($modelPackage->name == WPackage::PACKAGE_FLEXIBLE) {
                                    $package_name = Yii::t('web/portal', strtolower($modelPackage->name));
                                } else {
                                    $package_name = CHtml::encode($modelPackage->name);
                                }
                            ?>
                            <h3 class="title text-center">
                                Bạn đã đăng ký gói cước <span class="lbl_color_blue"><?= $package_name ?></span> giá
                                gói <span class="lbl_color_blue"><?= number_format($modelPackage->price, 0, "", ".") ?>
                                    đ</span>.<br><br>
                                <h5 style="text-align: center;">Vui lòng nhấn để xác nhận đăng ký.</h5>
                            </h3>

                            <div class="space_10"></div>
                            <div class="text-center form-group help-block error">
                                <?= (isset($msg)) ? $msg : ''; ?>
                            </div>
                            <div class="space_20"></div>
                            <div class="form-group text-center">
                                <?php echo CHtml::submitButton(Yii::t('web/portal', 'verify'), array(
                                    'name'  => 'confirm_register',
                                    'class' => 'btn btn_green'
                                )); ?>
                            </div>
                            <?php $this->endWidget(); ?>
                        </div>
                        <div class="space_10"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>