<?php
/**
 * @var $this WorldcupController
 * @var $model WWCReport
 */
?>

<?php $this->widget('booster.widgets.TbGridView', array(
    'id'           => 'wwcreport-grid',
    'dataProvider' => $model->search(),
    'template'     => '{items} {pager}',
    'itemsCssClass' => 'table table-bordered table-striped table-hover',
    'columns'      => array(
        array(
            'header'        => 'STT',
            'sortable'    => false,
            'type'        => 'raw',
            'value'       => '++$row',
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;width:50px'),
        ),
        array(
            'header'      => 'Họ tên',
            'sortable'    => false,
            'type'        => 'raw',
            'value'       => function($data){
                return $data->name;
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;width:160px'),
        ),
        array(
            'header'      => 'Số điện thoại',
            'sortable'    => false,
            'type'        => 'raw',
            'value'       => function($data){
                $value = substr($data->phone,0,4) . "xxxxxxx";
                return $value;
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;width:160px'),
        ),
        array(
            'header'      => 'E-mail',
            'sortable'    => false,
            'type'        => 'raw',
            'value'       => function($data){
                $value = $value = substr($data->email,0,4) . "xxxxxxx";
                return $value;
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;width:160px'),
        ),
//                array(
//                    'header'      => 'Trận đấu',
//                    'name'        => 'match_id',
//                    'sortable'    => false,
//                    'type'        => 'raw',
//                    'value'       => function($data){
//                        return CHtml::encode($data->match);
//                    },
//                    'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;width:120px'),
//                ),
//                array(
//                    'header'      => 'Vòng loại',
//                    'name'        => 'match_type',
//                    'sortable'    => false,
//                    'filter'      => CHtml::activeDropDownList($model,'match_type',WWCMatch::getListType(), array(
//                        'class' => 'form-control',
//                        'empty' => 'Tất cả',
//                    )),
//                    'type'        => 'raw',
//                    'value'       => function($data){
//                        return WWCMatch::getTypeLabel($data->match_type);
//                    },
//                    'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;width:120px'),
//                ),
//                array(
//                    'header'      => 'Đội lựa chọn',
//                    'name'        => 'team_selected',
//                    'sortable'    => false,
//                    'filter'      => CHtml::activeDropDownList($model,'team_selected',
//                        CHtml::listData(WWCTeam::getAllTeam(), 'code', 'name'),
//                        array(
//                            'class' => 'form-control',
//                            'empty' => 'Tất cả',
//                        )
//                    ),
//                    'type'        => 'raw',
//                    'value'       => function($data){
//                        return CHtml::encode(WWCTeam::getTeamName($data->team_selected));
//                    },
//                    'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;width:120px'),
//                ),
//                array(
//                    'header'      => 'Số may mắn',
//                    'name'        => 'lucky_number',
//                    'sortable'    => false,
//                    'type'        => 'raw',
//                    'value'       => function($data){
//                        return CHtml::encode($data->lucky_number);
//                    },
//                    'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;width:70px'),
//                ),
        array(
            'header'      => 'Thời gian dự đoán',
            'name'        => 'create_time',
            'sortable'    => false,
            'type'        => 'raw',
            'value'       => function($data){
                $value = date('H:i - d/m', strtotime($data->create_time));
                return CHtml::encode($value);
            },
            'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;width:120px'),
        ),
    ),
)); ?>