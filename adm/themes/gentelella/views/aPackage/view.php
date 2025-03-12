<?php
    /* @var $this APackageController */
    /* @var $model APackage */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'package') => array('admin'),
        $model->name
    );
?>
<?php $this->renderPartial('_menu_actions'); ?>

<?php $this->widget('booster.widgets.TbDetailView', array(
    'data'       => $model,
    'attributes' => array(
        'id',
        'name',
        array(
            'name'  => 'price',
            'type'  => 'raw',
            'value' => number_format($model->price, 0, "", "."),
        ),
        array(
            'name'  => 'type',
            'type'  => 'raw',
            'value' => $model->getPackageType($model->type),
        ),
        array(
            'name'  => 'status',
            'type'  => 'raw',
            'value' => $model->getStatusLabel(),
        ),
        'short_description',
        array(
            'name' => 'description',
            'type' => 'raw',
        ),
        'extra_params',
    ),
)); ?>
