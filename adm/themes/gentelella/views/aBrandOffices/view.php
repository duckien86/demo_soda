<?php
    /* @var $this ABrandOfficesController */
    /* @var $model ABrandOffices */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','location'),
        Yii::t('adm/label', 'brand_offices') => array('admin'),
        $model->name,
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'view') ?>: <?php echo CHtml::encode($model->name); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->widget('booster.widgets.TbDetailView', array(
            'data'       => $model,
            'attributes' => array(
                'id',
                'name',
                'address',
                'ward_code',
                'district_code',
                'province_code',
                'hotline',
                'descriptions',
                'head_office',
            ),
        )); ?>
    </div>
</div>