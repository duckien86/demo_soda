<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        'Quản lý link quảng cáo' => array('admin'),
        Yii::t('adm/actions', 'update'),
    );

?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'update') ?></h2>
        <div class="pull-right">
            <?php echo CHtml::link('Quản lý link quảng cáo', array('admin'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
</div>
