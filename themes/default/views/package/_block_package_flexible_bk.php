<?php
    /* @var $this PackageController */
    /* @var $data WPackage */
?>
<div class="item">
    <div class="title">
        <?= CHtml::link('ĐĂNG KÝ THEO NGÀY', Yii::app()->controller->createUrl('package/packageFlexible', array('period' => WPackage::PERIOD_1))); ?>
    </div>
    <div class="short_des">
        <ul>
            <li> Khách hàng tùy ý lựa chọn các gói và mức giá phù hợp với mục đích sử dụng</li>
            <li> Gói cước theo ngày thời hạn sử dụng đến 24h cùng ngày đăng ký</li>
        </ul>
    </div>
    <div class="txt_price">
        <i class="fa fa-tag" aria-hidden="true"></i>
        <span>Giá gói</span>
    </div>
    <div class="row_price">
        <div class="price fl">
            <span class="lbl_dis lbl_color_blue">Linh hoạt</span>
        </div>
        <div class="fr btn_view">
            <?= CHtml::link('Xem chi tiết', Yii::app()->controller->createUrl('package/packageFlexible', array('period' => WPackage::PERIOD_1))); ?>
        </div>
    </div>
    <div class="space_1"></div>
</div>

<div class="item">
    <div class="title">
        <?= CHtml::link('ĐĂNG KÝ THEO THÁNG', Yii::app()->controller->createUrl('package/packageFlexible', array('period' => WPackage::PERIOD_30))); ?>
    </div>
    <div class="short_des">
        <ul>
            <li> Khách hàng tùy ý lựa chọn các gói và mức giá phù hợp với mục đích sử dụng</li>
            <li> Gói cước theo ngày thời hạn sử dụng 30 ngày tính từ ngày đăng ký gói</li>
        </ul>
    </div>
    <div class="txt_price">
        <i class="fa fa-tag" aria-hidden="true"></i>
        <span>Giá gói</span>
    </div>
    <div class="row_price">
        <div class="price fl">
            <span class="lbl_dis lbl_color_blue">Linh hoạt</span>
        </div>
        <div class="fr btn_view">
            <?= CHtml::link('Xem chi tiết', Yii::app()->controller->createUrl('package/packageFlexible', array('period' => WPackage::PERIOD_30))); ?>
        </div>
    </div>
    <div class="space_1"></div>
</div>