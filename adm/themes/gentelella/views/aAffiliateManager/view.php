<?php
/* @var $this AAffiliateManagerController */
/* @var $model AAffiliateManager */

$this->breadcrumbs = array(
    Yii::t('adm/menu', 'manage_affiliate') => array('admin'),
    $model->name
);
?>

<?php $this->widget('booster.widgets.TbDetailView', array(
    'data'       => $model,
    'attributes' => array(
        'id',
        'name',
        'code',
        array(
            'name'  => 'status',
            'type'  => 'raw',
            'value' => function($data){
                return AAffiliateManager::getStatusLabel($data->status);
            }
        ),
    ),
)); ?>
