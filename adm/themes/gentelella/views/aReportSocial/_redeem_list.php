<?php $this->widget('booster.widgets.TbGridView', array(
    'id'            => 'redeem-history-grid',
    'dataProvider'  => $data_redeem_list,
    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
    'columns'       => array(
        array(
            'name'        => 'create_date',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->create_date)',
            'htmlOptions' => array('nowrap' => 'nowrap'),
        ),
        array(
            'name'        => 'package_code',
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->package_code)',
            'htmlOptions' => array('nowrap' => 'nowrap'),
        ),
        array(
            'name'        => 'point_amount',
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->point_amount)',
            'htmlOptions' => array('nowrap' => 'nowrap'),
        ),
        array(
            'name'        => 'transaction_id',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->transaction_id)',
            'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:right;vertical-align:middle;padding:10px'),
        ),
    ),
)); ?>