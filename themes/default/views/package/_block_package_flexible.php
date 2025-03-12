<?php
/**
 * @var $this PackageController
 * @var $list_package array
 * @var $type int
 * @var $activeId int
 */
$tabId = 'block_package_flexible';
$active = ($activeId && $activeId == $type) ? 'active' : '';
?>
<div role="tabpanel" class="tab-pane <?php echo $active?>" id="<?php echo $tabId?>" data-type="<?php echo $type?>">
    <div class="package_freedoo">
        <div class="container">
            <div class="content">
                <div class="block_package">
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="item_package">
                                <div class="title">
                                    <a href="<?php echo Yii::app()->controller->createUrl('package/packageFlexible', array('period' => WPackage::PERIOD_1))?>">
                                        <h4>ĐĂNG KÝ THEO NGÀY</h4>
                                    </a>
                                </div>

                                <div class="item_package_separator"></div>

                                <div class="package_description">
                                <div class="short_des">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="content">
                                                <p>Khách hàng tùy ý lựa chọn các gói và mức giá phù hợp với mục đích sử dụng</p>
                                                <p>&nbsp;</p>
                                                <p>Gói cước theo ngày thời hạn sử dụng 24h tính từ thời điểm đăng ký thành công</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <div class="price" style="padding: 0;">
                                    Linh hoạt
                                </div>

                                <div class="item_package_separator"></div>

                                <div class="action text-center">
                                    <a href="<?php echo Yii::app()->controller->createUrl('package/packageFlexible', array('period' => WPackage::PERIOD_1))?>" class="btn btn-register">
                                        <?php echo CHtml::encode(Yii::t('web/portal','register'));?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="item_package">
                                <div class="title">
                                    <a href="<?php echo Yii::app()->controller->createUrl('package/packageFlexible', array('period' => WPackage::PERIOD_30))?>">
                                        <h4>ĐĂNG KÝ THEO THÁNG</h4>
                                    </a>
                                </div>

                                <div class="item_package_separator"></div>

                                <div class="package_description">
                                <div class="short_des">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="content">
                                                <p>Khách hàng tùy ý lựa chọn các gói và mức giá phù hợp với mục đích sử dụng</p>
                                                <p>&nbsp;</p>
                                                <p>Gói cước theo ngày thời hạn sử dụng 30 ngày tính từ ngày đăng ký gói</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <div class="price" style="padding: 0">
                                    Linh hoạt
                                </div>

                                <div class="item_package_separator"></div>

                                <div class="action text-center">
                                    <a href="<?php echo Yii::app()->controller->createUrl('package/packageFlexible', array('period' => WPackage::PERIOD_30))?>" class="btn btn-register">
                                        <?php echo CHtml::encode(Yii::t('web/portal','register'));?>
                                    </a>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

