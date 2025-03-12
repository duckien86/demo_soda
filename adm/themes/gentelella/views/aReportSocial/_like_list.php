<?php $this->widget('booster.widgets.TbGridView', array(
    'id'            => 'alike-list-grid',
    'dataProvider'  => $data_likes_list,
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
            'name'        => 'sc_tbl_posts_id',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->getPostTitle($data->sc_tbl_posts_id))',
            'htmlOptions' => array('style' => 'vertical-align:middle;height: 20px;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            max-width:300px !important;
                        '),
        ),

    ),
)); ?>