<?php
    /* @var $this CheckoutController */
    /* @var $data WPackage */

    if (isset($class_col) && $class_col == TRUE) {
        $class_col = 'col-md-4';
    }
//    if ($data->price_discount > 0) {
//        $class_col        .= ' bg_discount';
//        $class_price      = 'txt_sm color_black';
//        $class_period     = 'txt_sm color_black';
//        $class_price_dis  = 'txt_lg color_blue';
//        $class_period_dis = 'txt_sm color_blue';
//    } else {
        $class_price      = 'txt_lg color_blue';
        $class_period     = 'txt_sm color_blue';
        $class_price_dis  = '';
        $class_period_dis = '';
//    }
?>
<div class="item <?= $class_col; ?>">
    <div class="title">
        <?= CHtml::link(CHtml::encode($data->name), 'javascript:void(0);', array('onClick' => 'getPackageDetail("' . $data->id . '")')); ?>
    </div>
    <div class="short_des">
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
            <?= CHtml::link('Xem chi tiết', 'javascript:void(0);', array('onClick' => 'getPackageDetail("' . $data->id . '")')); ?>
        </div>
    </div>
    <div class="space_1"></div>
</div>