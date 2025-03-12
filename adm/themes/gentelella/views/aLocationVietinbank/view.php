<?php
    /* @var $this ALocationVietinbankController */
    /* @var $model ALocationVietinbank */

    $this->breadcrumbs = array(
        Yii::t('adm/menu', 'manage_business'),
        Yii::t('adm/menu', 'account'),
        Yii::t('adm/label', 'location_vietinbank') => array('admin'),
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
        'access_key',
        'profile_id',
        'secret_key',
        'end_point',
        'qr_code_merchant_id',
        'vnp_TmnCode',
        'vnp_hashSecret',
        'vnp_end_point',
        'olpay_merchantId',
        'olpay_providerId',
        'pServiceCode',
        'pProviderId',
        'pMerchantId',
        'pEnd_point',
    ),
)); ?>
