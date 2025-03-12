<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','website_content'),
        Yii::t('adm/label', 'manage_cate_qa') => array('admin'),
        Yii::t('adm/actions', 'create'),
    );

?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'create') ?></h2>
        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/label', 'manage_cate_qa'), array('admin'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
</div>

