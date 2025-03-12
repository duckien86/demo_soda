<?php
/**
 * @var $this AWCReportController
 * @var $model AWCReport
 */
$this->breadcrumbs = array(
    Yii::t('adm/label', 'wc_report') => array('admin'),
);
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'wc_report'); ?></h2>

        <div class="clearfix"></div>
    </div>
    <?php echo $this->renderPartial('/aWCReport/_filter_area', array(
        'model' => $model
    ));?>
    <div class="x_content">

        <form method="post" target="_blank" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/aWCReport'); ?>">
            <?php echo CHtml::activeHiddenField($model,'match_id')?>
            <?php echo CHtml::activeHiddenField($model,'match_type')?>
            <?php echo CHtml::activeHiddenField($model,'team_selected')?>
            <?php echo CHtml::activeHiddenField($model,'lucky_number')?>
            <?php echo CHtml::activeHiddenField($model,'info')?>
            <?php echo CHtml::activeHiddenField($model,'status')?>
            <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
        </form>

        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'awcreport-grid',
                'dataProvider' => $model->search(),
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'header'        => 'STT',
                        'sortable'    => false,
                        'type'        => 'raw',
                        'value'       => '++$row',
                        'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:50px'),
                    ),
                    array(
                        'header'      => 'Thông tin người dự đoán',
                        'name'        => 'info',
                        'sortable'    => false,
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = $data->name . "<br/>"
                                . $data->phone . "<br/>"
                                . $data->email;
                            return $value;  
                        },
                        'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:160px'),
                    ),
                    array(
                        'header'      => 'Trận đấu',
                        'name'        => 'match_id',
                        'sortable'    => false,
                        'type'        => 'raw',
                        'value'       => function($data){
                            return CHtml::encode($data->match);
                        },
                        'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:120px'),
                    ),
                    array(
                        'header'      => 'Vòng loại',
                        'name'        => 'match_type',
                        'sortable'    => false,
                        'type'        => 'raw',
                        'value'       => function($data){
                            return AWCMatch::getTypeLabel($data->match_type);
                        },
                        'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:120px'),
                    ),
                    array(
                        'header'      => 'Đội lựa chọn',
                        'name'        => 'team_selected',
                        'sortable'    => false,
                        'type'        => 'raw',
                        'value'       => function($data){
                            return CHtml::encode(AWCTeam::getTeamName($data->team_selected));
                        },
                        'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:120px'),
                    ),
                    array(
                        'header'      => 'Số may mắn',
                        'name'        => 'lucky_number',
                        'sortable'    => false,
                        'type'        => 'raw',
                        'value'       => function($data){
                            return CHtml::encode($data->lucky_number);
                        },
                        'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:70px'),
                    ),
                    array(
                        'header'      => 'Thời gian dự đoán',
                        'name'        => 'create_time',
                        'sortable'    => true,
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = date('d/m/Y H:i:s', strtotime($data->create_time));
                            return CHtml::encode($value);
                        },
                        'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:80px'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'sortable'    => false,
                        'value'       => function ($data) {
                            if($data->status == AWCReport::WINNER){
                                return CHtml::encode(Yii::t('adm/label','winner'));
                            }else{
                                return CHtml::activeDropDownList($data, 'status',
                                    array(
                                        AWCReport::ACTIVE   => Yii::t('adm/label', 'active'),
                                        AWCReport::INACTIVE => Yii::t('adm/label', 'inactive')
                                    ),
                                    array('class'    => 'dropdownlist',
                                        'onChange' => "js:changeStatus($data->id,this.value)",
                                    )
                                );
                            }
                        },
                        'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:100px'),
                    ),
                    array(
                        'header'      => '',
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'template'    => '{gift}{ban}',
                        'buttons'     => array(
                            'gift' => array(
                                'label' => '<i class="fa fa-gift"></i>',
                                'url' => '"javascript:reward($data->id, \"$data->name\");"',
                                'options' => array(
                                    'style' => 'font-size: 18px',
                                    'title' => 'Trao thưởng',
                                ),
                                'visible'   => '$data->getBtnGift();'
                            ),
                            'ban' => array(
                                'label' => '<i class="fa fa-ban"></i>',
                                'url' => '"javascript:unreward($data->id, \"$data->name\");"',
                                'options' => array(
                                    'style' => 'font-size: 18px',
                                    'title' => 'Bỏ Trao thưởng',
                                ),
                                'visible'   => '$data->getBtnBan();'
                            ),
                        ),
                        'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:70px; text-align:center'),
                    ),
                ),
            )); ?>

        </div>
    </div>
</div>

<script language="javascript">
    function reward(id, name){
        var msg = 'Trao thưởng cho '+name+'?';
        if (confirm(msg)) {
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->controller->createUrl('aWCReport/reward')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: '<?php echo AWCReport::WINNER?>'},
                success: function (result) {
                    if(!result.error.length){
//                        window.location.reload();
                        $('#awcreport-grid').yiiGridView('update', {
                            data: $(this).serialize()
                        });
                    }else{
                        alert(result.error);
                    }

                    return false;
                }
            });
        }
    }

    function unreward(id, name){
        var msg = 'Bỏ Trao thưởng '+name+'?';
        if (confirm(msg)) {
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->controller->createUrl('aWCReport/changeStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: '<?php echo AWCReport::INACTIVE?>'},
                success: function (result) {
//                    window.location.reload();
                    $('#awcreport-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            });
        }
    }

    function changeStatus(id, status) {
        if (confirm('Bạn muốn thay đổi trạng thái?')) {
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->controller->createUrl('aWCReport/changeStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status},
                success: function (result) {
                    $('#awcreport-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            });
        }
    }
</script>