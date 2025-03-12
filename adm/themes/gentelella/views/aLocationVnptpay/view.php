<?php
    /* @var $this ALocationVnptpayController */
    /* @var $model ALocationVnptpay */

    $this->breadcrumbs = array(
        Yii::t('adm/menu', 'manage_business'),
        Yii::t('adm/menu', 'account'),
        Yii::t('adm/label', 'location_vnptpay') => array('admin'),
        AProvince::getProvinceNameByCode($model->id)
    );
?>

<?php $this->widget('booster.widgets.TbDetailView', array(
    'data'       => $model,
    'attributes' => array(
        'id',
        array(
            'name' => 'province',
            'type' => 'raw',
            'value' => function($data){
                return CHtml::encode(AProvince::getProvinceNameByCode($data->id));
            }
        ),
        'merchant_service_id',
        'service_id',
        'agency_id',
        'secret_key',
        'end_point',
    ),
)); ?>
