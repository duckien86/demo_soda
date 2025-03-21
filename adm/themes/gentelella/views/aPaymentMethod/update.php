<?php
    /* @var $this APaymentMethodController */
    /* @var $model APaymentMethod */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'payment_method') => array('admin'),
        $model->name
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'update') ?>: <?php echo CHtml::encode($model->name); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
</div>
