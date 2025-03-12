<?php
/**
 * @var $this APrepaidtopostpaidController
 * @var $model APrepaidToPostpaid
 * @var $response_code string
 * @var $response_msg string
 */
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
            'name'  => 'address',
            'type'  => 'raw',
            'value' => $model->address_detail . ' ' .
                AWard::getWardNameByCode($model->ward_code) . ', ' .
                AProvince::getProvinceNameByCode($model->province_code) . ', ' .
                ADistrict::getDistrictNameByCode($model->district_code),
        ),
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
        array(
            'name'  => 'status',
            'type'  => 'raw',
            'value' => function($data){
                $class = APrepaidToPostpaid::getLabelStatusClass($data->status);
                return "<b class='$class'>".APrepaidToPostpaid::getStatusLabel($data->status)."</b>";
            },
        ),
        'user_id',
    ),
)); ?>
<div class="text-center" style="margin-top:20px;">
    <?php if($model->status == APrepaidToPostpaid::PTP_APPROVE){
        echo CHtml::button(Yii::t('adm/label','approve'), array(
        'class' => 'btn btn-primary',
        'id'    => 'btnPtpConfirm',
        'onclick' => "approvePtp(this,'$model->id')",
        ));
    }?>

    <?php if($response_msg && $response_code){
        if(intval($response_code) == 1){
            echo "<label class='text-success'>Đơn hàng đang được tiến hành xử lí</label>";
        }else{
            echo "<label class='text-danger'>$response_msg</label>";
            echo "<script>console.log('$response_code');</script>";
        }
    } ?>
</div>