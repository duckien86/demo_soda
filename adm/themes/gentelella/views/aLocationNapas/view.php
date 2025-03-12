<?php
    /* @var $this ALocationNapasController */
    /* @var $model ALocationNapas */

    $this->breadcrumbs = array(
        Yii::t('adm/menu', 'manage_business'),
        Yii::t('adm/menu', 'account'),
        Yii::t('adm/label', 'location_napas') => array('admin'),
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
        'vpc_AccessCode',
        'vpc_Merchant',
        'secure_secret',
        'end_point',
        'bank_account',
        'bank_name',
    ),
)); ?>
