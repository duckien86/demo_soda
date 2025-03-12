<?php
    /* @var $this ACardStoreBusinessController */
    /* @var $model ACardStoreBusiness */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'card_detail') => array('admin'),
        $model->serial
    );
?>

<?php $this->widget('booster.widgets.TbDetailView', array(
    'data'       => $model,
    'attributes' => array(
        'id',
        'serial',
        array(
            'name'  => 'pin',
            'type'  => 'raw',
            'value' => function($data){
                $pin = substr($data->pin, 0, 5) . "xxxxxxx";
                return $pin;
            }
        ),
        array(
            'name'  => 'value',
            'type'  => 'raw',
            'value' => number_format($model->value,0,',','.'),
        ),
        array(
            'name'  => 'status',
            'type'  => 'raw',
            'value' => function($data){
                $status = '<span class="'.ACardStoreBusiness::getStatusLabelClass($data->status).'">'.ACardStoreBusiness::getStatusLabel($data->status).'</span>';
                return $status;
            },
        ),
        'import_code',
        array(
            'name'  => 'create_date',
            'type'  => 'raw',
            'value' => date('d-m-Y H:i:s', strtotime($model->create_date)),
        ),
        array(
            'name'  => 'expired_date',
            'type'  => 'raw',
            'value' => date('d-m-Y H:i:s', strtotime($model->expired_date)),
        ),
        array(
            'name'  => 'active_date',
            'type'  => 'raw',
            'value' => function($data){
                $active_date = (isset($data->active_date)) ? date('d-m-Y H:i:s', strtotime($data->active_date)) : null;
                return $active_date;
            },
        ),
        array(
            'label' => Yii::t('adm/label', 'order_id'),
            'name'  => 'order_id',
            'type'  => 'raw',
            'value' => function($data){
                $value = null;
                if(!empty($data->order_id)){
                    $order = AFTOrders::model()->findByPk($data->order_id);
                    $value =  $order->code;
                }
                return $value;
            }
        ),
        array(
            'name'  => 'type',
            'type'  => 'raw',
            'value' => ACardStoreBusiness::getTypeLabel($model->type),
        ),
        array(
            'label' => Yii::t('adm/label', 'customer'),
            'name'  => 'purchase_by',
            'type'  => 'raw',
            'value' => function($data){
                $value = null;
                if(!empty($data->purchase_by)){
                    $user = AFTUsers::model()->findByPk($data->purchase_by);
                    $value = $user->company . ' - ' . $user->fullname;
                }
                return $value;
            }
        ),
        'note',
    ),
)); ?>
