<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'news') => array('admin'),
        $model->order_id
    );
?>

<?php $this->widget('booster.widgets.TbDetailView', array(
    'data'       => $model,
    'attributes' => array(
        'id',
        array(
            'name'  => 'order_id',
            'type'  => 'raw',
            'value' => function ($data) {
                return CHtml::encode($data->order_id);
            }
        ),
        array(
            'name'  => 'partner',
            'type'  => 'raw',
            'value' => function ($data) {
                return CHtml::encode($data->partner);
            }
        ),
        array(
            'name'  => 'payment_method',
            'type'  => 'raw',
            'value' => function ($data) {
                return $data->getPaymentMethod($data->payment_method);
            }
        ),
        array(
            'name'  => 'transaction_id',
            'type'  => 'raw',
            'value' => function ($data) {
                return CHtml::encode($data->transaction_id);
            }
        ), array(
            'name'  => 'request',
            'type'  => 'raw',
            'value' => function ($data) {
                return CHtml::encode($data->request);
            },
            'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'word-break: break-word;'),
        ), array(
            'name'  => 'response',
            'type'  => 'raw',
            'value' => function ($data) {
                return CHtml::encode($data->response);
            }
        ),
        array(
            'name'  => 'note',
            'type'  => 'raw',
            'value' => function ($data) {
                return CHtml::encode($data->note);
            }
        ),
        array(
            'name'  => 'status',
            'type'  => 'raw',
            'value' => function ($data) {
                return CHtml::encode($data->status);
            }
        ),
        array(
            'name'  => 'create_date',
            'type'  => 'raw',
            'value' => function ($data) {
                return CHtml::encode($data->create_date);
            }
        ),
        array(
            'name'  => 'response_data_type',
            'type'  => 'raw',
            'value' => function ($data) {
                return CHtml::encode($data->response_data_type);
            }
        ),
        array(
            'name'  => 'endpoint',
            'type'  => 'raw',
            'value' => function ($data) {
                return CHtml::encode($data->endpoint);
            }
        ),
    ),
)); ?>
