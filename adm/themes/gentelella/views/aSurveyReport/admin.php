<?php
    /* @var $this ASurveyReportController */
    /* @var $model ASurveyReport */

    $this->breadcrumbs = array(
        Yii::t('adm/label', 'survey_report') => array('admin'),
        Yii::t('adm/actions', 'manage'),
    );
?>

<div class="x_panel">
    <div class="x_title">
        <h2><?= Yii::t('adm/label', 'survey_report'); ?></h2>

        <div class="clearfix"></div>
    </div>
    <?php $this->renderPartial('_filter_area', array('model' => $model)); ?>
    <div class="x_content">
        <form method="post" target="_blank" action="<?php echo Yii::app()->createAbsoluteUrl('excelExport/surveyReport'); ?>">
            <input type="hidden" name="excelExport[start_date]" value="<?php echo $model->start_date ?>">
            <input type="hidden" name="excelExport[end_date]" value="<?php echo $model->end_date ?>">
            <?php echo CHtml::submitButton(Yii::t('adm/label', 'export_csv'), array('style' => 'float: right;margin-right: 15px; text-align: right;', 'id' => 'btnExport', 'class' => 'btn btn-warning', 'download' => '')); ?>
        </form>
        <div class="table-responsive tbl_style center">
            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'           => 'asurveyreport-grid',
                'dataProvider' => $model->search(),
                'columns'      => array(
                    array(
                        'name'        => 'id',
                        'htmlOptions' => array('style' => 'width:50px;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'user',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return ACustomers::getName($data->user_id);
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'order_id',
                        'type'        => 'raw',
                        'value'       => '$data->order_id',
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'phone',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return AOrders::getOrderPhoneContact($data->order_id);
                        },
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'question',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return ASurveyQuestion::getQuestionContent($data->question_id);
                        },
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'answer',
                        'type'        => 'raw',
                        'value'       => function($data){
                            return ASurveyAnswer::getAnswerContent($data->answer_id);
                        },
                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'name'        => 'content',
                        'type'        => 'raw',
                        'value'       => '$data->content',
                        'filter'      => FALSE,
                        'htmlOptions' => array('style' => 'width:120px;word-break: break-word;vertical-align:middle;'),
                    ),
//                    array(
//                        'name'        => 'is_right',
//                        'type'        => 'raw',
//                        'value'       => '$data->is_right',
//                        'htmlOptions' => array('style' => 'word-break: break-word;vertical-align:middle;'),
//                    ),
                    array(
                        'name'        => 'create_date',
                        'filter'      => FALSE,
                        'htmlOptions' => array('style' => 'width:90px;text-align: center;word-break: break-word;vertical-align:middle;'),
                    ),
                    array(
                        'header'      => Yii::t('adm/actions', 'action'),
                        'class'       => 'booster.widgets.TbButtonColumn',
                        'template'    => '{view}',
                        'htmlOptions' => array('nowrap' => 'nowrap', 'style' => 'width:100px;text-align:center;vertical-align:middle;padding:10px'),
                    ),
                ),
            )); ?>
        </div>
    </div>
</div>
