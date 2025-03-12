<?php
    /* @var $this ASurveyController */
    /* @var $model ASurvey */

    $this->breadcrumbs = array(
        Yii::t('adm/menu','manage_business'),
        Yii::t('adm/menu','survey'),
        Yii::t('adm/label', 'survey') => array('admin'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'survey'); ?></h2>

        <div class="pull-right">
            <?php echo CHtml::link(Yii::t('adm/actions', 'create'), array('create'), array('class' => 'btn btn-success')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'asurvey-grid',
                'dataProvider' => $model->search(),
                'itemsCssClass' => 'table table-bordered table-striped table-hover jambo_table responsive-utilities',
                'columns'      => array(
                    array(
                        'name'        => 'id',
                        'htmlOptions' => array('style' => 'width:70px;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'name',
                        'type'        => 'raw',
                        'value'       => 'CHtml::link(CHtml::encode($data->name), array(\'update\', \'id\' => $data->id))',
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'point',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return number_format($data->point,0,',','.');
                        },
                        'htmlOptions' => array('style' => 'text-align:center;width:90px;word-break: break-word;vertical-align:middle;'),
                        'headerHtmlOptions' => array('style' => 'text-align:center'),
                    ),
                    array(
                        'header'      => Yii::t('adm/label','limit'),
                        'name'        => 'limit',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return number_format($data->limit,0,',','.');
                        },
                        'htmlOptions' => array('style' => 'text-align:center;width:90px;word-break: break-word;vertical-align:middle;'),
                        'headerHtmlOptions' => array('style' => 'text-align:center'),
                    ),
                    array(
                        'name'        => 'start_date',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return date('d/m/Y', strtotime($data->start_date));
                        },
                        'htmlOptions' => array('style' => 'width:110px;text-align: center;word-break: break-word;vertical-align:middle;'),
                        'headerHtmlOptions' => array('style' => 'text-align:center'),
                    ),
                    array(
                        'name'        => 'end_date',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return date('d/m/Y', strtotime($data->end_date));
                        },
                        'htmlOptions' => array('style' => 'width:110px;text-align: center;word-break: break-word;vertical-align:middle;'),
                        'headerHtmlOptions' => array('style' => 'text-align:center'),
                    ),
                    array(
                        'name'        => 'status',
                        'type'        => 'raw',
                        'value'       => function ($data) {
                            return CHtml::activeDropDownList($data, 'status',
                                array(
                                    ASurvey::SURVEY_ACTIVE   => Yii::t('adm/label', 'active'),
                                    ASurvey::SURVEY_INACTIVE => Yii::t('adm/label', 'inactive')
                                ),
                                array('class'    => 'dropdownlist',
                                      'onChange' => "js:changeStatus($data->id,this.value)",
                                )
                            );
                        },
                        'htmlOptions' => array('width' => '120px', 'style' => 'vertical-align:middle;'),
                        'headerHtmlOptions' => array('style' => 'text-align:center'),
                    ),
                    array(
                        'header'      => Yii::t('adm/actions', 'action'),
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'width:100px;text-align:center;vertical-align:middle;padding:10px'),
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
                url: '<?=Yii::app()->controller->createUrl('aSurvey/changeStatus')?>',
                crossDomain: true,
                dataType: 'json',
                data: {id: id, status: status},
                success: function (result) {
                    $('#asurvey-grid').yiiGridView('update', {
                        data: $(this).serialize()
                    });
                    return false;
                }
            });
        }
    }
</script>
