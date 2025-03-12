<?php $this->widget('booster.widgets.TbGridView', array(
    'id'            => 'apost-history-grid',
    'dataProvider'  => $data_post_list,
    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
    'columns'       => array(
        array(
            'name'        => 'create_date',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->create_date, array(\'update\', \'id\' => $data->id))',
            'htmlOptions' => array('nowrap' => 'nowrap'),
        ),
        array(
            'name'        => 'content',
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->content)',
            'htmlOptions' => array('style' => 'vertical-align:middle;height: 20px;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            max-width:200px !important;
                        '),
        ),
        array(
            'name'        => 'total_comment',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->total_comment)',
            'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:right;vertical-align:middle;padding:10px'),
        ),
        array(
            'name'        => 'total_like',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->total_like)',
            'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:right'),
        ),
        array(
            'name'        => 'get_award',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->checkAward($data->get_award, $data->id))',
            'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:right'),
        ),
        array(
            'name'        => 'Lý do',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function ($data) {
                if ($data->status == APosts::INACTIVE) {
                    $return = "Bị ẩn bài viết";
                } else {
                    $return = "Đăng bài viết";
                }
                return $return;
            },
            'htmlOptions' => array('nowrap' => 'nowrap'),
        ),
        array(
            'name'        => 'status',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function ($data) {
                if ($data->status == APosts::ACTIVE) {
                    $return = Yii::t('adm/label', 'active');
                } else if ($data->status == APosts::INACTIVE) {
                    $return = Yii::t('adm/label', 'inactive');
                } else if ($data->status == APosts::NOCOMMENT) {
                    $return = "Cấm bình luận";
                } else {
                    $return = "Chờ duyệt";
                }

                return $return;
            },
            'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:left;'),
        ),

    ),
)); ?>