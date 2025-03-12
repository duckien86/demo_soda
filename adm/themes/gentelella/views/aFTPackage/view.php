<?php
    /* @var $this AFTPackageController */
    /* @var $model AFTPackage */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'product_manage') => array('admin'),
        CHtml::encode($model->name),
    );
    $this->menu        = array(
        array('label' => Yii::t('adm/actions', 'create'), 'url' => array('create')),
        array('label' => Yii::t('adm/actions', 'update'), 'url' => array('update', 'id' => $model->id)),
        array('label' => Yii::t('adm/label', 'product_manage'), 'url' => array('admin')),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?php echo Yii::t('adm/actions', 'view') ?>: <?php echo CHtml::encode($model->name); ?></h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->widget('booster.widgets.TbDetailView', array(
            'data'       => $model,
            'attributes' => array(
                'id',
                'name',
                'code',
                array(
                    'name'  => 'price',
                    'type'  => 'raw',
                    'value' => number_format($model->price, 0, "", "."),
                ),
                'description',
                array(
                    'name'  => 'type',
                    'type'  => 'raw',
                    'value' => AFTPackage::getTypeLabel($model->type),
                ),
                array(
                    'name'  => 'status',
                    'type'  => 'raw',
                    'value' => $model->getStatusLabel(),
                ),
            ),
        )); ?>
    </div>
</div>
