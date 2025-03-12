<?php
    /* @var $this CheckoutController */
    /* @var $package WPackage */

    if ($package->price_discount) {
        $class_price      = 'txt_sm color_black';
        $class_period     = 'txt_sm color_black';
        $class_price_dis  = 'txt_lg color_blue';
        $class_period_dis = 'txt_sm color_blue';
    } else {
        $class_price      = 'txt_lg color_blue';
        $class_period     = 'txt_sm color_blue';
        $class_price_dis  = '';
        $class_period_dis = '';
    }
?>
<div class="package_info">
    <div class="col-md-4 no_pad_left">
        <div class="thumbnail">
            <?= CHtml::image(Yii::app()->params->upload_dir . $package->thumbnail_2, 'image', array()); ?>
        </div>
    </div>
    <div class="col-md-8">
        <div class="title"><?= CHtml::encode($package->name); ?></div>
        <div class="<?= $class_price ?>"><?= number_format($package->price, 0, "", "."); ?>đ</div>
        <?php if ($package->price_discount > 0): ?>
            <div class="<?= $class_period_dis ?>">
                <span class="lbl_dis">Chỉ còn: </span>
                <?= number_format($package->price_discount, 0, "", "."); ?>đ
            </div>
        <?php endif; ?>
        <div class="short_des">
            <?= nl2br(CHtml::encode($package->short_description)); ?>
        </div>
    </div>
    <div class="space_1"></div>
</div>
<div class="space_10"></div>
<div class="full_description">
    <div class="title">Thông tin chi tiết</div>
    <div class="description">
        <?= $package->description; ?>
    </div>
</div>
<div class="space_10"></div>