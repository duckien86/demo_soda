<?php
/* @var $this APrepaidtopostpaidController */
/* @var $model APrepaidToPostpaid */

$this->breadcrumbs = array(
    Yii::t('adm/label', 'prepaid_to_postpaid') => array('admin'),
    $model->id
);
?>

<?php $this->widget('booster.widgets.TbDetailView', array(
    'data'       => $model,
    'attributes' => array(
        array(
            'label'=> Yii::t('adm/label','ptp_id'),
            'name'  => 'id',
        ),
        'msisdn',
        array(
            'label'=> Yii::t('adm/label','ptp_order_id'),
            'name'  => 'order_id',
        ),
        array(
            'name'  => 'package',
            'type'  => 'raw',
            'value' => function($data){
                $return = '';
                $package = APackage::model()->findByAttributes(array('code' => $data->package_code));
                if($package){
                    $price = $package->price;
                    if($package->price_discount > 0){
                        $price = $package->price_discount;
                    }else if ($package->price_discount == -1){
                        $price = 0;
                    }
                    $return = $package->name . ' - ' . number_format($price,0,',','.') . ' VNĐ';
                }
                return $return;
            },
        ),
        'full_name',
        'personal_id',
        array(
            'name'  => 'province_code',
            'type'  => 'raw',
            'value' => AProvince::getProvinceNameByCode($model->province_code),
        ),
        array(
            'name'  => 'district_code',
            'type'  => 'raw',
            'value' => ADistrict::getDistrictNameByCode($model->district_code),
        ),
        array(
            'name'  => 'ward_code',
            'type'  => 'raw',
            'value' => AWard::getWardNameByCode($model->ward_code),
        ),
        'address_detail',
        'promo_code',
        'otp',
        array(
            'name'  => 'create_date',
            'type'  => 'raw',
            'value' => $model->create_date,
        ),
        array(
            'name'  => 'receive_date',
            'type'  => 'raw',
            'value' => $model->receive_date,
        ),
        array(
            'name'  => 'finish_date',
            'type'  => 'raw',
            'value' => $model->finish_date,
        ),
        'request_id',
        array(
            'name'  => 'status',
            'type'  => 'raw',
            'value' => APrepaidToPostpaid::getStatusLabel($model->status),
        ),
        'user_id',
    ),
)); ?>
