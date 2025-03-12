<?php
    /* @var $this ABrandOfficesController */
    /* @var $model ABrandOffices */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','location'),
        'Phòng bán hàng' => array('admin'),
        $model->name,
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'view') ?>: <?php echo CHtml::encode($model->name); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">

        <?php $this->widget('zii.widgets.CDetailView', array(
            'data'       => $model,
            'attributes' => array(
                'id',
                'name',
                'ward_code',
                'district_code',
                'province_code',
                'code',
                'location_type',
            ),
        )); ?>
    </div>
</div>
