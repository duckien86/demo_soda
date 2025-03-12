<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','website_content'),
        Yii::t('adm/label', 'news') => array('admin'),
        $model->title
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'update') ?>: <?php echo CHtml::encode($model->title); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
</div>
