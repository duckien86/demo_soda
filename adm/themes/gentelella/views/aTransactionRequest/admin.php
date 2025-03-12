<?php
    /* @var $this ANewsController */
    /* @var $model ANews */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'news') => array('admin'),
        Yii::t('adm/actions', 'manage'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2>Transaction Request</h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php $this->widget('booster.widgets.TbGridView', array(
            'id'            => 'atransaction-request-grid',
            'dataProvider'  => $model->search(),
            'filter'        => $model,
            'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
            'columns'       => array(
                array(
                    'name'        => 'order_id',
                    'type'        => 'raw',
//                    'filter'      => FALSE,
                    'value'       => function ($data) {
                        return $data->order_id;
                    },
                    'htmlOptions' => array('nowrap' => 'nowrap'),
                ),
                array(
                    'name'        => 'partner',
                    'type'        => 'raw',
//                    'filter'      => FALSE,
                    'value'       => function ($data) {
                        return $data->partner;
                    },
                    'htmlOptions' => array('nowrap' => 'nowrap'),
                ),
                array(
                    'name'        => 'payment_method',
                    'type'        => 'raw',
//                    'filter'      => FALSE,
                    'value'       => function ($data) {
                        return $data->getPaymentMethod($data->payment_method);
                    },
                    'htmlOptions' => array('nowrap' => 'nowrap'),
                ),
                array(
                    'name'        => 'transaction_id',
                    'type'        => 'raw',
//                    'filter'      => FALSE,
                    'value'       => function ($data) {
                        return $data->transaction_id;
                    },
                    'htmlOptions' => array('nowrap' => 'nowrap'),
                ),
//                array(
//                    'name'        => 'request',
//                    'type'        => 'raw',
//                    'filter'      => FALSE,
//                    'value'       => function ($data) {
//                        return $data->request;
//                    },
//                    'htmlOptions' => array('nowrap' => 'nowrap'),
//                ),
//                array(
//                    'name'        => 'response',
//                    'type'        => 'raw',
//                    'filter'      => FALSE,
//                    'value'       => function ($data) {
//                        return $data->response;
//                    },
//                    'htmlOptions' => array('nowrap' => 'nowrap'),
//                )
                array(
                    'name'        => 'create_date',
                    'type'        => 'raw',
                    'filter'      => FALSE,
                    'value'       => function ($data) {
                        return $data->create_date;
                    },
                    'htmlOptions' => array('nowrap' => 'nowrap'),
                ),
                array(
                    'header'      => 'Thao tác',
                    'class'       => 'booster.widgets.TbButtonColumn',
                    'template'    => '{view}',
                    'buttons'     => array(
                        'view' => array(
                            'label' => '',
                            'url'   => 'Yii::app()->createUrl("aTransactionRequest/view", 
                                array("order_id"=>$data->order_id,"payment_method"=>$data->payment_method, "partner" => $data->partner))',
                        ),
                    ),
                    'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
                ),
            ),
        )); ?>
    </div>
</div>

