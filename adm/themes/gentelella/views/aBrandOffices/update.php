<?php
    /* @var $this ABrandOfficesController */
    /* @var $model ABrandOffices */
    /* @var $province */
    /* @var $district */
    /* @var $ward */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','location'),
        Yii::t('adm/label', 'brand_offices') => array('admin'),
        CHtml::encode($model->name),
    );
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'update') ?>: <?php echo CHtml::encode($model->name); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->renderPartial('_form', array(
            'model'    => $model,
//            'province' => $province,
//            'district' => $district,
//            'ward'     => $ward
        )); ?>
    </div>
</div>