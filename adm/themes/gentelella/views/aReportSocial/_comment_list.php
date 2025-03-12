<?php $this->widget('booster.widgets.TbGridView', array(
    'id'            => 'acomment-list-grid',
    'dataProvider'  => $data_comment_list,
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
            'name'        => 'content',
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->content)',
            'htmlOptions' => array('style' => 'vertical-align:middle;height: 20px;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            max-width:100px !important;
                        '),
        ),
        array(
            'name'        => 'sc_tbl_post_id',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->getPostTitle($data->sc_tbl_post_id))',
            'htmlOptions' => array('style' => 'vertical-align:middle;height: 20px;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            max-width:300px !important;
                        '),
        ),
        array(
            'name'        => 'total_like',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->total_like)',
            'htmlOptions' => array('nowrap' => 'nowrap'),
        ),
        array(
            'name'        => 'get_award',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::encode($data->checkAward($data->get_award, $data->id))',
            'htmlOptions' => array('nowrap' => 'nowrap'),
        ),
        array(
            'name'        => 'Lý do',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => function ($data) {
                if ($data->status == AComments::INACTIVE) {
                    $return = "Bị block bình luận";
                } else {
                    $return = "Bình luận bài viết";
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
                if ($data->status == AComments::ACTIVE) {
                    $return = Yii::t('adm/label', 'active');
                } else {
                    $return = Yii::t('adm/label', 'inactive');
                }
                return $return;
            },
            'htmlOptions' => array('nowrap' => 'nowrap'),
        ),
    ),
)); ?>