<?php
    /* @var $this TopupController */
    /* @var $model TTopupQueue */

    $this->breadcrumbs = array(
        Yii::t('tourist/label', 'topup') => array('admin'),
    );
?>

<?php $this->widget('booster.widgets.TbDetailView', array(
    'data'       => $model,
    'htmlOptions'=> array(
        'class' => 'table table-bordered table-striped table-responsive table-hover td-width-50',
        'style' => 'margin-top: 20px',
    ),
    'attributes' => array(
        array(
            'name'  => 'serial',
            'type'  => 'raw',
            'value' => function($data){
                $value = '?';
                if(!empty($data->serial)){
                    $value = $data->serial;
                }
                return $value;
            },
        ),
        array(
            'name'  => 'card_pin',
            'type'  => 'raw',
            'value' => function($data){
                $value = ($data->status == TTopupQueue::TOPUP_SUCCESS) ? $data->pin : substr($data->pin, 0, 5)."xxxxxxx";
                return $value;
            },
        ),
        array(
            'name'  => 'value',
            'type'  => 'raw',
            'value' => function($data){
                $value = '?';
                if(!empty($data->value)){
                    $value = number_format($data->value,0,',','.');
                }
                return $value;
            },
        ),
        array(
            'name'  => 'msisdn',
            'type'  => 'raw',
            'value' => substr($model->msisdn, 0, 5)."xxxxxxx",
        ),
        array(
            'name'  => 'create_date',
            'type'  => 'raw',
            'value' => date('d/m/Y', strtotime($model->create_date)),
        ),
        array(
            'name'  => 'user_create',
            'type'  => 'raw',
            'value' => $model->user_create,
        ),
        array(
            'name'  => 'status',
            'type'  => 'raw',
            'value' => TTopupQueue::getStatusLabel($model->status),
        ),
        array(
            'name'  => 'create_date',
            'type'  => 'raw',
            'value' => date('d/m/Y H:i:s', strtotime($model->create_date)),
        ),
        array(
            'name'  => 'topup_date',
            'type'  => 'raw',
            'value' => function($data){
                $value = '';
                if(!empty($data->topup_date)){
                    $value = date('d/m/Y H:i:s', strtotime($data->topup_date));
                }
                return $value;
            },
        ),
        'note',
    ),
)); ?>
