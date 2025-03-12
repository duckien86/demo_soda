<?php
    /* @var $this CskhShipperController */
    /* @var $model CskhShipper */

    $this->breadcrumbs = array(
        Yii::t('adm/menu', 'manage_business'),
        'Quản lý NV giao vận' => array('admin'),
        Yii::t('adm/actions', 'update'),
    );

    $this->menu = array(
        array('label' => Yii::t('cskh/menu', 'manage_shipper'), 'url' => array('admin')),
    );
?>

<div class="x_panel container-fluid">
    <div class="x_title title-form">
        <h1><?php echo Yii::t('cskh/menu', 'update') ?></h1>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">

        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
</div>
