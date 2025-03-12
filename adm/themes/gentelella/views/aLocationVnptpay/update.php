<?php
    /* @var $this ALocationVnptpayController */
    /* @var $model ALocationVnptpay */

    $province = AProvince::getProvinceNameByCode($model->id);

    $this->breadcrumbs = array(
        Yii::t('adm/menu', 'manage_business'),
        Yii::t('adm/menu', 'account'),
        Yii::t('adm/label', 'location_vnptpay') => array('admin'),
        CHtml::encode($province)
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'update') ?>: <?php echo CHtml::encode($province); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
</div>