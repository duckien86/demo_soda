<?php $this->widget('booster.widgets.TbGridView', array(
    'id'            => 'aposts-grid_' . $type,
    'dataProvider'  => $model->search($type),
    'filter'        => $model,
    'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
    'columns'       => array(
        array(
            'name'        => 'content',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::link($data->content, array(\'view\', \'id\' => $data->id))',
            'htmlOptions' => array('style' => 'vertical-align:middle;height: 20px;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            max-width:200px !important;
                        '),
        ),
        array(
            'name'        => 'sso_id',
            'type'        => 'raw',
            'filter'      => FALSE,
            'value'       => function ($data) {
                return ACustomers::getName($data->sso_id);
            },
            'htmlOptions' => array('nowrap' => 'nowrap'),
        ),
        array(
            'name'        => 'total_comment',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::link($data->total_comment, array(\'view\', \'id\' => $data->id))',
            'htmlOptions' => array('nowrap' => 'nowrap'),
        ),
        array(
            'name'        => 'total_like',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::link($data->total_like, array(\'view\', \'id\' => $data->id))',
            'htmlOptions' => array('nowrap' => 'nowrap'),
        ),
        array(
            'name'        => 'post_category_id',
            'filter'      => CHtml::activeDropDownList($model, 'post_category_id',
                $model->getAllPostCate(),
                array('class' => 'form-control', 'prompt' => 'Tất cả', 'style' => 'width:120px;')
            ),
            'type'        => 'raw',
            'value'       => 'CHtml::link($data->getPostCate($data->post_category_id), array(\'view\', \'id\' => $data->id))',
            'htmlOptions' => array('nowrap' => 'nowrap'),
        ),

        array(
            'name'        => 'create_date',
            'filter'      => FALSE,
            'type'        => 'raw',
            'value'       => 'CHtml::link($data->create_date, array(\'view\', \'id\' => $data->id))',
            'htmlOptions' => array('nowrap' => 'nowrap'),
        ),
        array(
            'name'        => 'status',
            'type'        => 'raw',
            'filter'      => FALSE,
            'value'       => function ($data) {
                return CHtml::activeDropDownList($data, 'status',
                    APosts::getAllStatus(),
                    array('class'    => 'dropdownlist',
                          'onChange' => "js:changeStatus($data->id,this.value,'$data->sso_id')",
                    )
                );
            },
            'htmlOptions' => array('width' => '130px', 'style' => 'vertical-align:middle;'),
        ),
        array(
            'header'      => Yii::t('adm/actions', 'action'),
            'class'       => 'booster.widgets.TbButtonColumn',
            'template'    => '{update}&nbsp;&nbsp;{view}',
            'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'text-align:center;vertical-align:middle;padding:10px'),
        ),
    ),
)); ?>