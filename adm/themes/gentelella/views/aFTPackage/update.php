<?php
    /* @var $this AFTPackageController */
    /* @var $model AFTPackage */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'product_manage') => array('admin'),
        Yii::t('adm/actions', 'create'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'update') ?>: <?php echo $model->name; ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->renderPartial('_form', array(
            'model' => $model,
        )); ?>
    </div>
</div>