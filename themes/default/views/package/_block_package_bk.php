<?php
    /* @var $this PackageController */
    /* @var $data WPackage */

    if (isset($class_col) && $class_col == TRUE) {
        $class_col = 'col-md-4';
    }
    if ($data->price_discount > 0) {
        $class_col        .= ' bg_discount';
        $class_price      = 'txt_sm color_black';
        $class_period     = 'txt_sm color_black';
        $class_price_dis  = 'txt_lg lbl_color_blue';
        $class_period_dis = 'txt_sm lbl_color_blue';
    } else {
        $class_price      = 'txt_lg lbl_color_blue';
        $class_period     = 'txt_sm lbl_color_blue';
        $class_price_dis  = '';
        $class_period_dis = '';
    }
?>
<div class="item <?= $class_col; ?>">
    <div class="title">
        <?= CHtml::link(CHtml::encode($data->name), Yii::app()->controller->createUrl('package/detail', array('id' => $data->id)), array()); ?>
    </div>
    <div class="short_des">
        <?php if ($data->vip_user >= WPackage::VIP_USER): ?>
            <div class="lbl_color_blue font_16">
                Gói cước ưu đãi dành riêng cho CTV
            </div>
        <?php endif; ?>
        <ul>
            <?php
                $str = CHtml::encode($data->short_description);
                $str = '<li> ' . str_replace(array("\r", "\n\n", "\n"), array('', "\n", "</li>\n<li> "), trim($str, "\n\r")) . '</li>';
                echo $str;
            ?>
        </ul>
    </div>
    <div class="txt_price">
        <i class="fa fa-tag" aria-hidden="true"></i>
        <span>Giá gói</span>
    </div>
    <div class="row_price">
        <div class="price fl">
            <span class="<?= $class_price ?>">
                <?= number_format($data->price, 0, "", ".") ?> đ
            </span>
            <span class="<?= $class_period ?>">/<?= $data->getPackagePeriodLabel($data->period); ?></span>
        </div>
        <div class="fr btn_view">
            <?= CHtml::link('Xem chi tiết', Yii::app()->controller->createUrl('package/detail', array('id' => $data->id)), array()); ?>
        </div>
    </div>
    <?php if ($data->price_discount > 0): ?>
        <div class="row_price row_price_dis">
            <div class="price">
                <span class="lbl_dis lbl_color_blue">Chỉ còn: </span>
                <span class="<?= $class_price_dis ?>">
                <?= number_format($data->price_discount, 0, "", ".") ?> đ
            </span>
                <span class="<?= $class_period_dis ?>">/<?= $data->getPackagePeriodLabel($data->period); ?></span>
            </div>
        </div>
    <?php endif; ?>
    <div class="space_1"></div>
</div>