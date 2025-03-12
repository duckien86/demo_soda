<?php
/* @var $this ANewsCommentsController */
/* @var $model ANewsComments */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'manage_comments') => array('admin'),
    CHtml::encode($model->id)
);
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'update') ?>: #<?php echo CHtml::encode($model->id); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
</div>