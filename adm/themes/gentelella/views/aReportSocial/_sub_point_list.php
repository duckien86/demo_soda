<?php $this->widget('booster.widgets.TbGridView', array(
    'id'            => 'asub-point-history-grid',
    'dataProvider'  => $data_sub_point_list,
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
            'name'        => 'event',
            'type'        => 'raw',
            'value'       => function ($data) {
                if ($data->amount > 0) {
                    $type = APointHistory::TYPE_ADD;
                } else {
                    $type = APointHistory::TYPE_SUB;
                }

                return CHtml::encode($data->convertEvent($data->event, $type));
            },
            'htmlOptions' => array('nowrap' => 'nowrap'),
        ),

        array(
            'name'        => 'amount',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->amount)',
            'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:right;vertical-align:middle;padding:10px'),
        ),
        array(
            'name'        => 'amount_before',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->amount_before)',
            'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:right;vertical-align:middle;padding:10px'),
        ),
        array(
            'name'        => 'note',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->note)',
            'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:left;vertical-align:middle;padding:10px'),
        ),


    ),
)); ?>