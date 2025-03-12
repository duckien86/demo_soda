<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'manage_hobbies') => array('admin'),
        Yii::t('adm/actions', 'update'),
    );

    $this->menu = array(
        array('label' => Yii::t('adm/label', 'manage_hobbies'), 'url' => array('admin')),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'update') ?></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
</div>

