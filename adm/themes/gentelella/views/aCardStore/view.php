<?php
/**
 * @var $this ACardStoreController
 * @var $model ACardStore
 */
$this->pageTitle = Yii::t('adm/menu','manage_card_store');

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
                $pin = ($data->status == ACardStore::CARD_SUCCESS) ? $data->pin : substr($data->pin, 0, 5) . "xxxxxxx";
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
                $status = '<span class="'.ACardStore::getStatusLabelClass($data->status).'">'.ACardStore::getStatusLabel($data->status).'</span>';
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
        'order_id',
        array(
            'name'  => 'type',
            'type'  => 'raw',
            'value' => ACardStore::getTypeLabel($model->type),
        ),
        array(
            'label' => Yii::t('adm/label', 'customer'),
            'name'  => 'purchase_by',
        ),
        'note',
    ),
)); ?>
