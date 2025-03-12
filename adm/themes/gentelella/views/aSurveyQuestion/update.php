<?php
    /* @var $this ASurveyController */
    /* @var $model ASurvey */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'survey') => array('admin'),
        Yii::t('adm/label','question') . $model->id
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'update') ?>: <?php echo CHtml::encode(Yii::t('adm/label','question') . $model->id); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
</div>
