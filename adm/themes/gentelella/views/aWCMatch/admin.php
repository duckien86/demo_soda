<?php
/**
 * @var $this AWCMatchController
 * @var $model AWCMatch
 */
$this->breadcrumbs = array(
    Yii::t('adm/label', 'wc_match') => array('admin'),
);
?>
<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'wc_match'); ?></h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'awcmatch-grid',
                'dataProvider' => $model->search(),
                'filter'       => $model,
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'name'        => 'team_name_1',
                        'sortable'    => false,
                        'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:120px'),
                    ),
                    array(
                        'name'        => 'team_name_2',
                        'sortable'    => false,
                        'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:120px'),
                    ),
                    array(
                        'name'        => 'type',
                        'sortable'    => false,
                        'filter'      => CHtml::activeDropDownList($model,'type',AWCMatch::getListType(),array(
                            'class'=>'form-control',
                            'empty'=>'Tất cả',
                        )),
                        'type'        => 'raw',
                        'value'       => function($data){
                            return CHtml::encode(AWCMatch::getTypeLabel($data->type));
                        },
                        'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:100px'),
                    ),
                    array(
                        'name'        => 'start_time',
                        'sortable'    => false,
                        'filter'      => false,
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = date('H:i d-m-Y', strtotime($data->start_time));
                            return CHtml::encode($value);
                        },
                        'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:160px'),
                    ),
                    array(
                        'header'      => 'Tỉ số',
                        'filter'      => false,
                        'type'        => 'raw',
                        'value'       => function($data){
                            $value = '';
                            if(!empty($data->score_1) || !empty($data->score_2)){
                                if(empty($data->score_1)){
                                    $data->score_1 = 0;
                                }
                                if(empty($data->score_2)){
                                    $data->score_2 = 0;
                                }
                                $value = $data->score_1 . ' - ' .$data->score_2;
                            }
                            return $value;
                        },
                        'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:80px'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'sortable'    => false,
                        'filter'      => CHtml::activeDropDownList(
                            $model,
                            'status',
                            AWCMatch::getListStatus(),
                            array('empty' => Yii::t('adm/label', 'all'), 'class' => 'form-control')
                        ),
                        'value'       => function ($data) {
                            if($data->status == AWCMatch::COMPLETE){
                                return CHtml::encode(Yii::t('adm/label','complete'));
                            }else{
                                return CHtml::activeDropDownList($data, 'status',
                                    array(
                                        AWCMatch::ACTIVE   => Yii::t('adm/label', 'active'),
                                        AWCMatch::INACTIVE => Yii::t('adm/label', 'inactive')
                                    ),
                                    array('class'    => 'dropdownlist',
                                        'onChange' => "js:changeStatus($data->id,this.value)",
                                    )
                                );
                            }
                        },
                        'htmlOptions' => array('nowrap'=>'nowrap','style' => 'word-break: break-word;vertical-align:middle;width:120px'),
                    ),
                    array(
                        'header'      => '',
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'template'    => '{view} {update} {delete}',
                        'buttons'     => array(
                            'update' => array(
                                'visible' => '$data->getBtnUpdate()'
                            ),
                            'delete' => array(
                                'visible' => '$data->getBtnDelete()'
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
    function changeStatus(id, status) {
        if (confirm('Bạn muốn thay đổi trạng thái?')) {
            $.ajax({
                type: "POST",
                url: '<?=Yii::app()->controller->createUrl('aWCMatch/changeStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status},
                success: function (result) {
                    $('#awcmatch-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            });
        }
    }
</script>